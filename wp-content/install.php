<?php
/**
 * @version   2.12 October 17, 2012
 * @author    RocketTheme, LLC http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

add_action('shutdown', 'rockettheme_custom_install');
add_filter("user_has_cap", "rt_add_cap", 10, 3);

function rt_add_cap($hascaps, $caps, $args)
{
    foreach ($caps as $cap) {
        $hascaps[$cap] = true;
    }
    return $hascaps;
}

function rockettheme_custom_install()
{
    global $wp_import, $pagenow, $step, $wp_rewrite;
    if ($pagenow != 'install.php' || $step != 2) {
        return;
    }
    $theme_info_file = ABSPATH . "/wp-content/rockettheme/installer/theme.ini";
    if (!file_exists($theme_info_file)) {
        echo "Unable to find theme info file at " . $theme_info_file;
        return;
    }
    $theme_info           = parse_ini_file($theme_info_file);
    $sample_data_file     = rt_change_path(ABSPATH . "/wp-content/themes/" . $theme_info['template'] . "/sample/sample_data.xml");
    $sample_widgets_file  = rt_change_path(ABSPATH . "/wp-content/themes/" . $theme_info['template'] . "/sample/sample_widgets.php");
    $sample_data_sql_file = ABSPATH . "/wp-content/themes/" . $theme_info['template'] . "/sample/sample_data.sql";
    
    // check if the sample data sql file exists
    if(file_exists($sample_data_sql_file)) {
	    $sample_data_sql_file = rt_change_path_sql($sample_data_sql_file);
    }

    // change the theme to the RL theme
    switch_theme($theme_info['template'], $theme_info['stylesheet']);
    delete_option('theme_switched');
    if (file_exists($sample_data_file)) {
        require_once(ABSPATH . '/wp-content/rockettheme/installer/importer.php');
        $wp_import = new RocketLauncher_Import();
        $wp_import->import($sample_data_file, true);
        unlink($sample_data_file);
    }
    // process the sample widgets import file.
    // this file should have been exported using the rokwidgets_export plugin
    if (file_exists($sample_widgets_file)) {
        delete_option('sidebars_widgets');
        include_once($sample_widgets_file);
        unlink($sample_widgets_file);
    }

    if (file_exists($sample_data_sql_file)) {
        rt_runSqlFile($sample_data_sql_file);
        unlink($sample_data_sql_file);
    }
    rename(__FILE__, __FILE__ . '.run');
	$wp_rewrite->init();
	$wp_rewrite->flush_rules();
}

/**
 * Change the tokens in the XML
 */
function rt_change_path($file)
{
    //global $wp_import;
    $xml = file_get_contents($file);
    $xml = preg_replace("/\@RT_SITE_URL\@/", get_bloginfo('wpurl'), $xml);

    $upload_path = trim(get_option('upload_path'));
    if (empty($upload_path)) {
        $dir = WP_CONTENT_DIR . '/uploads';
    } else {
        $dir = $upload_path;
        if ('wp-content/uploads' == $upload_path) {
            $dir = WP_CONTENT_DIR . '/uploads';
        } elseif (0 !== strpos($dir, ABSPATH)) {
            // $dir is absolute, $upload_path is (maybe) relative to ABSPATH
            $dir = path_join(ABSPATH, $dir);
        }
    }

    $temp   = tempnam($dir, "rt_wp_import");
    $handle = fopen($temp, "w");
    fwrite($handle, $xml);
    $file = $temp;
    fclose($handle);
    return $file;
}

function rt_change_path_sql($file)
{
    //global $wp_import;
    $sql = file_get_contents($file);
    $wp_url = get_bloginfo('wpurl');
    $wp_url = str_replace('/', '\\\/', $wp_url);
    $sql = preg_replace("/\@RT_SITE_URL\@/", $wp_url, $sql);

    $upload_path = trim(get_option('upload_path'));
    if (empty($upload_path)) {
        $dir = WP_CONTENT_DIR . '/uploads';
    } else {
        $dir = $upload_path;
        if ('wp-content/uploads' == $upload_path) {
            $dir = WP_CONTENT_DIR . '/uploads';
        } elseif (0 !== strpos($dir, ABSPATH)) {
            // $dir is absolute, $upload_path is (maybe) relative to ABSPATH
            $dir = path_join(ABSPATH, $dir);
        }
    }

    $temp   = tempnam($dir, "rt_wp_import_sql");
    $handle = fopen($temp, "w");
    fwrite($handle, $sql);
    $file = $temp;
    fclose($handle);
    return $file;
}

function rt_runSqlFile($file)
{
    global $wpdb;
    $buffer  = file_get_contents($file);
    $queries = rt_splitSql($buffer);

    foreach ($queries as $query) {
        if (trim($query) != ''){
            $sql = rt_replacePrefix($query, $wpdb->prefix);
            $wpdb->query($sql);
        }
    }
}

function rt_splitSql($sql)
{
    $start   = 0;
    $open    = false;
    $char    = '';
    $end     = strlen($sql);
    $queries = array();

    for ($i = 0; $i < $end; $i++) {
        $current = substr($sql, $i, 1);
        if (($current == '"' || $current == '\'')) {
            $n = 2;

            while (substr($sql, $i - $n + 1, 1) == '\\' && $n < $i) {
                $n++;
            }

            if ($n % 2 == 0) {
                if ($open) {
                    if ($current == $char) {
                        $open = false;
                        $char = '';
                    }
                } else {
                    $open = true;
                    $char = $current;
                }
            }
        }

        if (($current == ';' && !$open) || $i == $end - 1) {
            $queries[] = substr($sql, $start, ($i - $start + 1));
            $start     = $i + 1;
        }
    }

    return $queries;
}

/**
 * This function replaces a string identifier <var>$prefix</var> with the string held is the
 * <var>tablePrefix</var> class variable.
 *
 * @param   string  $sql         The SQL statement to prepare.
 * @param   string  $old_prefix  The common table prefix.
 *
 * @return  string  The processed SQL statement.
 *
 * @since   11.1
 */
function rt_replacePrefix($sql, $new_prefix, $old_prefix = '#__')
{
    // Initialize variables.
    $escaped   = false;
    $startPos  = 0;
    $quoteChar = '';
    $literal   = '';

    $sql = trim($sql);
    $n   = strlen($sql);

    while ($startPos < $n) {
        $ip = strpos($sql, $old_prefix, $startPos);
        if ($ip === false) {
            break;
        }

        $j = strpos($sql, "'", $startPos);
        $k = strpos($sql, '"', $startPos);
        if (($k !== false) && (($k < $j) || ($j === false))) {
            $quoteChar = '"';
            $j         = $k;
        } else {
            $quoteChar = "'";
        }

        if ($j === false) {
            $j = $n;
        }

        $literal .= str_replace($old_prefix, $new_prefix, substr($sql, $startPos, $j - $startPos));
        $startPos = $j;

        $j = $startPos + 1;

        if ($j >= $n) {
            break;
        }

        // quote comes first, find end of quote
        while (true) {
            $k       = strpos($sql, $quoteChar, $j);
            $escaped = false;
            if ($k === false) {
                break;
            }
            $l = $k - 1;
            while ($l >= 0 && $sql{$l} == '\\') {
                $l--;
                $escaped = !$escaped;
            }
            if ($escaped) {
                $j = $k + 1;
                continue;
            }
            break;
        }
        if ($k === false) {
            // error in the query - no end quote; ignore it
            break;
        }
        $literal .= substr($sql, $startPos, $k - $startPos + 1);
        $startPos = $k + 1;
    }
    if ($startPos < $n) {
        $literal .= substr($sql, $startPos, $n - $startPos);
    }

    return $literal;
}
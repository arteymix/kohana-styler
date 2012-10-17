<?php

/**
 * Apply a css stylesheet on any HTML document.
 *  
 * @package CSSApplier
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license    http://kohanaphp.com/license.html
 */
class CSSApplier {

    public static function apply($html_path, $css_path, $output_path = "") {

        $css_parser = new Parser(file_get_contents($css_path));


        $html_parser = file_get_html($html_path);

        foreach ($css_parser->getAllSelector() as $selector) {
            $html_parser->find($selector->getSelector())->style = $selector->getRules();
        }

        return $html_parser->save($output_path);

        // Apply selected css rules in the style tags
        // Return the output
    }

}

?>

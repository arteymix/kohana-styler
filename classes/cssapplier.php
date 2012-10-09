<?php

class CSSApplier {

    public static function apply($html_path, $css_path) {

        $css_parser = new Parser(file_get_contents($css_path));


        $html_parser = file_get_html($html_path);

        foreach ($css_parser->getAllSelector() as $selector) {
            $html_parser->find($selector->getSelector()) . __set("style", $selector->getRules());
        }

        return $html_parser->save();

        // Apply selected css rules in the style tags
        // Return the output
    }

}

?>

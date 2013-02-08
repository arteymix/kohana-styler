<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @package Styler
 */
class Styler {

    public static function factory($html, $css) {
        return new Styler($html, $css);
    }

    private $html, $css;

    public function __construct($html, $css) {
        $this->html = $html;
        $this->css = $css;
        spl_autoload_register(array($this, "autoload"));
        require_once Kohana::find_file("vendor", "simplehtmldom/simple_html_dom");
    }

    /**
     * Namespace autoloader for vendor files
     * @param type $class_name
     */
    public function autoload($class_name) {
        require_once Kohana::find_file("vendor/PHP-CSS-Parser/lib", str_replace("\\", "/", $class_name));
    }

    public function render() {

        // Parse the css
        $css_parser = new Sabberworm\CSS\Parser($this->css);

        // Looping through the document and applying rules
        $parsed_html = str_get_html($this->html->render());

        $declaration_blocks = $css_parser->parse()->getAllDeclarationBlocks();

        foreach ($declaration_blocks as $declaration_block) {

            // Apply

            foreach ($declaration_block->getSelectors() as $selector) {


                foreach ($parsed_html->find($selector) as $element) {

                    $element->style .= implode("", $declaration_block->getRules());
                }
            }
        }

        return $parsed_html;
    }

    public function __toString() {
        return $this->render();
    }

}

?>

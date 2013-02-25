<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @todo implémenter un système de cache
 * 
 * @package Styler
 * @author Guillaume Poirier-Morency
 */
class Kohana_Styler  {

    /**
     * 
     * @param string $html
     * @param string $css
     * @return Styler
     */
    public static function factory($html, $css) {
        return new Styler($html, $css);
    }

    private $parsed_html, $parsed_css;

    public function __construct($html, $css) {

        spl_autoload_register(array($this, "autoload"));
        
        require_once Kohana::find_file("vendor", "simplehtmldom/simple_html_dom");

        $this->html($html);
        $this->css($css);
    }

    /**
     * Namespace autoloader for vendor files
     * @param type $class_name
     */
    private function autoload($class_name) {
        if($file = Kohana::find_file("vendor/PHP-CSS-Parser/lib", str_replace("\\", "/", $class_name))) {
            require_once $file;
        }
    }

    private function parse_html($content) {
        return str_get_html($content);
    }

    private function parse_css($content) {

        // Parse the css
        $css_parser = new Sabberworm\CSS\Parser($content);

        return $css_parser->parse();
    }

    /**
     * Setter for HTML content.
     * @param string $content
     */
    public function html($content) {
        $this->parsed_html = $this->parse_html($content);
    }

    /**
     * Setter for CSS content.
     * @param string $content
     */
    public function css($content) {
        $this->parsed_css = $this->parse_css($content);
    }

    public function render() {

        $declaration_blocks = $this->parsed_css->getAllDeclarationBlocks();

        foreach ($declaration_blocks as $declaration_block) {

            // Apply

            foreach ($declaration_block->getSelectors() as $selector) {


                foreach ($this->parsed_html->find($selector) as $element) {

                    $element->style .= implode("", $declaration_block->getRules());
                }
            }
        }

        return (string) $this->parsed_html;
    }

    public function __toString() {
        return $this->render();
    }

}

?>

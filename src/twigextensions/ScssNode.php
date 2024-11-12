<?php
/**
 * SCSS plugin for Craft CMS 3.x
 *
 * SCSS
 *
 * @link      https://chasegiunta.com
 * @copyright Copyright (c) 2018 Chase Giunta
 */

namespace chasegiunta\scss\twigextensions;

use chasegiunta\scss\Scss;
use Twig\Compiler;
use Twig\Node\Node;
use Twig\Node\NodeCaptureInterface;

/**
 * Compile twig node parser
 *
 * @author    chasegiunta
 * @package   SCSS
 * @since     1.0.0
 */
class ScssNode extends Node implements NodeCaptureInterface
{
    // Public Methods
    // =========================================================================
    /**
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler): void
    {
        // Left for reference:
        // $crunched = $this->getAttribute('crunched');
        // $expanded = $this->getAttribute('expanded');

        $template = $this->getTemplateName();

        $compiler
            ->addDebugInfo($this);

        $compiler
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write("\$_compiledBody = ob_get_clean();\n");

        $attributes = serialize($this->attributes);

        $compiler
            ->write("\$attributes = '$attributes';\n");

        $compiler
            ->write("echo ".Scss::class."::\$plugin->scssService->scss(\$_compiledBody, \$attributes);\n");
    }
}

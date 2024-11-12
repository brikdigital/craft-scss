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

use chasegiunta\scss\twigextensions\ScssNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * SCSS twig token parser
 *
 * @author    chasegiunta
 * @package   SCSS
 * @since     1.0.0
 */
class ScssTokenParser extends AbstractTokenParser
{
    // Public Methods
    // =========================================================================
    /**
     * Parses {% scss %}...{% endscss %} tags
     *
     * @param Token $token
     *
     * @return \chasegiunta\scss\twigextensions\ScssNode
     */
    public function parse(Token $token): ScssNode
    {
        $lineNo = $token->getLine();
        $stream = $this->parser->getStream();

        $attributes = [
            'expanded'      => false,
            'compressed'    => false,
            'debug'         => false,
        ];

        if ($stream->test(Token::NAME_TYPE, 'expanded')) {
            $attributes['expanded'] = true;
            $stream->next();
        }

        if ($stream->test(Token::NAME_TYPE, 'compressed')) {
            $attributes['compressed'] = true;
            $stream->next();
        }

        if ($stream->test(Token::NAME_TYPE, 'debug')) {
            $attributes['debug'] = true;
            $stream->next();
        }

        $stream->expect(Token::BLOCK_END_TYPE);
        $nodes['body'] = $this->parser->subparse([$this, 'decideScssEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new ScssNode($nodes, $attributes, $lineNo, $this->getTag());
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return 'scss';
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    public function decideScssEnd(Token $token): bool
    {
        return $token->test('endscss');
    }
}
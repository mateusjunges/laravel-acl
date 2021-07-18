<?php

namespace Junges\ACL\Tests\BladeDirectives;

use Junges\ACL\Tests\TestCase;

class BladeDirectivesTestCase extends TestCase
{
    private $blade;

    public function setUp(): void
    {
        parent::setUp();

        $this->blade = resolve('blade.compiler');
    }

    /**
     * @param string $expected
     * @param string $expression
     * @param array $variables
     * @param string $message
     */
    protected function assertDirectiveOutput(
        string $expected,
        string $expression = '',
        array $variables = [],
        string $message = ''
    ) {
        $compiled = $this->blade->compileString($expression);

        /*
         * Normally using eval() would be a big no-no, but when you're working on a templating
         * engine it's difficult to avoid.
         */
        ob_start();
        extract($variables);

        eval(' ?>'.$compiled.'<?php ');

        $output = ob_get_clean();

        $this->assertEquals($expected, $output, $message);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests;

use SimpleFW\Templating\Templating;

final class TemplatingTest
{
    use AssertTrait;

    public function run(): void
    {
        $templating = new Templating(__DIR__.'/templates');

        $this->assert(
            '<p>Hello World!</p>',
            trim($templating->render('one.php')),
        );

        $this->assert(
            '<table>
            <tr>
            <td>One1</td>
            <td>One2</td>
            <td>One3</td>
        </tr>
            <tr>
            <td>Two1</td>
            <td>Two2</td>
            <td>Two3</td>
        </tr>
    </table>',
            trim($templating->render('two.php', [
                'rows' => [
                    ['one' => 'One1', 'two' => 'One2', 'three' => 'One3'],
                    ['one' => 'Two1', 'two' => 'Two2', 'three' => 'Two3'],
                ],
            ])),
        );
    }
}

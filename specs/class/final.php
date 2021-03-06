<?php

declare(strict_types=1);

/*
 * This file is part of the humbug/php-scoper package.
 *
 * Copyright (c) 2017 Théo FIDRY <theo.fidry@gmail.com>,
 *                    Pádraic Brady <padraic.brady@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'meta' => [
        'title' => 'Final class declaration',
        // Default values. If not specified will be the one used
        'prefix' => 'Humbug',
        'whitelist' => [],
    ],

    'Declaration in the global namespace: add prefixed namespace.' => <<<'PHP'
<?php

final class A {}
----
<?php

namespace Humbug;

final class A
{
}

PHP
    ,

    'Declaration in a namespace: prefix the namespace.' => <<<'PHP'
<?php

namespace Foo;

final class A {}
----
<?php

namespace Humbug\Foo;

final class A
{
}

PHP
    ,

    'Declaration of a whitelisted final class: append aliasing.' => [
        'whitelist' => ['Foo\A'],
        'payload' => <<<'PHP'
<?php

namespace Foo;

final class A {}
----
<?php

namespace Humbug\Foo;

final class A
{
}
\class_alias('Humbug\\Foo\\A', 'Foo\\A', \false);

PHP
        ],

    'Multiple declarations in different namespaces: prefix each namespace.' => <<<'PHP'
<?php

namespace X {
    final class A {}
}

namespace Y {
    final class B {}
}

namespace Z {
    final class C {}
}
----
<?php

namespace Humbug\X;

final class A
{
}
namespace Humbug\Y;

final class B
{
}
namespace Humbug\Z;

final class C
{
}

PHP
    ,
];

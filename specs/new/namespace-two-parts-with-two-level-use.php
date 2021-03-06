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
        'title' => 'New statement call of a namespaced class imported with a use statement in a namespace',
        // Default values. If not specified will be the one used
        'prefix' => 'Humbug',
        'whitelist' => [],
    ],

    [
        'spec' => <<<'SPEC'
New statement call of a class via a use statement:
- prefix the namespace
- prefix the use statement
- prefix the call
- transform the call into a FQ call
SPEC
        ,
        'payload' => <<<'PHP'
<?php

namespace X {
    class Foo {}
}

namespace X\Foo {
    class Bar {}
}

namespace A {
    use X\Foo;
    
    new Foo\Bar();
}
----
<?php

namespace Humbug\X;

class Foo
{
}
namespace Humbug\X\Foo;

class Bar
{
}
namespace Humbug\A;

use Humbug\X\Foo;
new \Humbug\X\Foo\Bar();

PHP
    ],

    [
        'spec' => <<<'SPEC'
FQ new statement call of a class via a use statement:
- prefix the namespace
- prefix the use statement
- prefix the call
- transform the call into a FQ call
SPEC
        ,
        'payload' => <<<'PHP'
<?php

namespace X {
    class Foo {}
}

namespace Foo {
    class Bar {}
}

namespace A {
    use X\Foo;
    
    new \Foo\Bar();
}
----
<?php

namespace Humbug\X;

class Foo
{
}
namespace Humbug\Foo;

class Bar
{
}
namespace Humbug\A;

use Humbug\X\Foo;
new \Humbug\Foo\Bar();

PHP
    ],

    [
        'spec' => <<<'SPEC'
New statement call of a whitelisted class via a use statement:
- prefix the namespace
- prefix the use statement
- do not prefix the call
- transform the call into a FQ call
SPEC
        ,
        'whitelist' => ['X\Foo\Bar'],
        'payload' => <<<'PHP'
<?php

namespace X {
    class Foo {}
}

namespace X\Foo {
    class Bar {}
}

namespace A {
    use X\Foo;
    
    new Foo\Bar();
}
----
<?php

namespace Humbug\X;

class Foo
{
}
namespace Humbug\X\Foo;

class Bar
{
}
\class_alias('Humbug\\X\\Foo\\Bar', 'X\\Foo\\Bar', \false);
namespace Humbug\A;

use Humbug\X\Foo;
new \Humbug\X\Foo\Bar();

PHP
    ],

    [
        'spec' => <<<'SPEC'
FQ new statement call of a non-whitelisted class via a use statement:
- prefix the namespace
- prefix the use statement
- prefix the call
- transform the call into a FQ call
SPEC
        ,
        'whitelist' => ['X\Foo\Bar'],
        'payload' => <<<'PHP'
<?php

namespace X {
    class Foo {}
}

namespace Foo {
    class Bar {}
}

namespace A {
    use X\Foo;
    
    new \Foo\Bar();
}
----
<?php

namespace Humbug\X;

class Foo
{
}
namespace Humbug\Foo;

class Bar
{
}
namespace Humbug\A;

use Humbug\X\Foo;
new \Humbug\Foo\Bar();

PHP
    ],
];

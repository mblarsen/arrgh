# CHANGELOG

## v0.9.0

Changed: Cut the `gh` out of `arrgh` so all functions that was `arrgh_*`, is now `arr_*`. You can still change this with a custom prefix set to `arrgh`

## v0.8.0

Changed: `map_ass()` is not `map_assoc()` for consistency.

## v0.7.0

Changed: Rewrote `isCollection()` and `depth()`. `[]` is now a collection. Depth is now zero based.

Changed: Native `array_column()` will filter away null values—i.e when column is missing. This is equivalent to `array_filter(array_map(callable, array))`. This means that you cannot use
  `array_column()` for `array_multisort()` since array size no longer matches. This behaviour has been changed in _Arrgh_, so to achieve the same result as the native column-function you'll ned to filter it afterwards.

## v0.6.1

New: Added `head()`, `tail()`, `first()`, `last()`, `partition()`, `odd()`, `even()`.

## v0.6.0

New: Certain sort and compare functions that takes a callable will now automatically have their callable wrapped in a PHP version aware callable.

New: Terminator methods will now return an _Arrgh_ object when the result is an array.

New: `asort()` behaves like `uasort()` so it is now now mapped to `uassort()` with a wrapped callable

New: `shift()` and `unshift()` was missing.

Changed: `getPhpSortDirection()` is now `getSortDirection()`

## v0.5.8

Changed: `getSortDirection()` takes a computed direction.

## v0.5.7

New: `keepOnce()` added. Will break the chain after keeping it once

## v0.5.5

New: `min()` and `max` added

Added script to build functions in case anyone needs it

## v0.5.4

Bugfixes: Various patches

New: Global functions are now pre-build for `arrgh` prefix

## v0.5.1

Bugfix: sortBy DESC for array with two items would fail

Bugfix: sortBy fix for discrepancy for how zero-value sort comparison is handled in PHP5 vs PHP7

## v0.5.0

Fixed: Collapsing was not reliable, fixed + unittests added

Changed: `!>` replaced with option to use negative index. So `children.!>.name` to get name of last child is now: `children.-1.name`

New: Added `depth()`

## v0.4.0

New: Implements ArrayAccess and Iterator. Iterator returns Arrgh instead of arrays.

New: _Arrgh_ objects can be passed as arguments instead of arrays.

New: _copyValue_ type functions like `array_pop` that changes the array but also returns a value now sets the array to it can be access with `toArray()`.

New: Default behaviour of value functions is to return the value. E.g. `pop()`. The new function `keepChain()` will return the `Arrgh` object rather than the value.

## v0.3.0

Changed `dotGet` to `get`

## v0.2.1

Added: `get()` function [[see examples](#examples)]

Added: `isCollection()` function

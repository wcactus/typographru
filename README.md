# Laravel 5 screen typography module for russian language

### Usage / Demonstration

For unicode text:

```php
$input = 'Falcon Heavy (букв. с англ. "Тяжёлый "Сокол"") - американская ракета-носитель (РН) сверхтяжёлого  класса с возможностью повторного использования 1-й ступени , спроектированная и произведённая-таки компанией SpaceX...';
echo TypographRu::typograph($input);
/*
output (with nonbreaking spaces, nonbreaking dashes and other special unicode symbols):
Falcon Heavy (букв. с англ. «Тяжёлый „Сокол“») – американская ракета-носитель (РН) сверхтяжёлого класса с возможностью повторного использования 1‑й ступени, спроектированная и произведённая‑таки компанией SpaceX…
*/
```

The screen typography is best viewed here for win-1251-encoded text:

```php
$input = 'Falcon Heavy (букв. с англ. "Тяжёлый "Сокол"") - американская ракета-носитель (РН) сверхтяжёлого  класса с возможностью повторного использования 1-й ступени , спроектированная и произведённая-таки компанией SpaceX...';
echo TypographRu::typograph($input);
/*
output:
Falcon Heavy (букв. с&nbsp;англ. &laquo;Тяжёлый &bdquo;Сокол&ldquo;&raquo;)&nbsp;&ndash; американская ракета-носитель (РН) сверхтяжёлого класса с&nbsp;возможностью повторного использования <nobr>1-й</nobr> ступени, спроектированная и&nbsp;<nobr>произведённая-таки</nobr> компанией SpaceX&hellip;
*/
```

### Installation

Add TypographRu to your Laravel project: `composer require wcactus/typographru`

Facade and service provider will be autoloaded.

The middle-length dash (the same as _&amp;ndash;_ HTML entity) is used by default. If you prefer the longest dash (the same as _&amp;mdash;_ HTML entity), you need to publish configuration file info config/typographru.php using `php artisan vendor:publish --tag=typographru` command and then set _use_mdash_ configuration property to _true_.

### Methods

**TypographRu::typograph(string $text, [string $language = null, [boolean $forceQuotes = true]])**

Applies screen typography to unicode string.
It is assumed that all special characters in the source string are represented by unicode characters, rather than HTML entities.
If some HTML entities are used as special characters, they will be converted to unicode characters (except of non-typography-specific entities).
All changes in the returned string are performed using unicode characters.

**string $text**: text to typograph.

**string $language**: Optional. Can be _TypographRu::LANGUAGE_RU_, _TypographRu::LANGUAGE_EN_ or null (i.e. auto-detect russian language). Affects only the kind of quotation marks, the screen typography does not fully applied to the english language. May be useful in multilanguage websites.

**boolean $forceQuotes**: Optional. If true, the previously placed «correct» quotes will be re-placed again.

**TypographRu::typographWin1251(string $text, [string $language = null, [boolean $forceQuotes = true]])**

Applies screen typography to win-1251-encoded string.
It is assumed that all special characters in the source string are represented by HTML entities due to the limitations of Win-1251 encoding.
All changes in the returned string are performed using HTML entities.

**string $text**: text to typograph.

**string $language**: Optional. Can be _TypographRu::LANGUAGE_RU_, _TypographRu::LANGUAGE_EN_ or null (i.e. auto-detect russian language). Affects only the kind of quotation marks, the screen typography does not fully applied to the english language. May be useful in multilanguage websites.

**boolean $forceQuotes**: Optional. If true, the previously placed «correct» quotes will be re-placed again.
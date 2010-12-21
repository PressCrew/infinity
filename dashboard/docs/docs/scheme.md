## The Scheme

Infinity uses something that we call "The Scheme".
While this sounds like the Title of a Jerry Bruckheimer movie, it's
actually quite an accurate description of what Infinity allows you to do.

> *Scheme* - An orderly combination of related parts.
> a systematic plan for a course of action

So what does this mean? To explain this it's best to stay close
to the WordPress terminology and use the comparison with the well knows
Parent/Child structure introduced in WordPress 2.7.

> A WordPress child theme is a theme that inherits the functionality
> of another theme, called the parent theme, and allows you to modify,
> or add to, the functionality of that parent theme.

The parent/child structure has been a very popular method to harness the
power of WordPress Frameworks because it allows you to modify the styling
and layout of a parent theme to any extent without editing the files of the
parent theme itself. That way, when the parent theme is updated, your
modifications are preserved.

Infinity takes this concept to the next level by allowing you to create an
infinite number of themes that can extend each other the same way a child theme
extends a parent theme. You can take this extremely powerful feature as far
as you wish, and it gives you total freedom in how you set up your
theme development work flow.

![Scheme Hierarchy](infinity://admin:image/docs/scheme_hierarchy.jpg)

Now we hear you say; Isn't the Parent -> Child functionality more then enough
for mose cases?

The answer is Yes! In most cases you can use this structure to
create custom themes based on Infinity. It all works exactly as it normally would,
and if you don't feel the need to go deeper then that, there is no special
configuration required.

### Customize Child Themes without having to worry about upgrades

If you have modified a Child Theme of a Infinity, you can easily create a
Grandchild Theme to further customize it exactly as you wish. This means that if your
Child Themes gets an update you can safely upgrade and enjoy the new features,
without losing your customizations! This solves a very big issue for most WordPress
parent themes and frameworks, and we're very glad that we have managed to solve this
problem with Infinity.
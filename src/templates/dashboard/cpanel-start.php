
<h2>Welcome to the Infinity family!</h2>

<p>
	Infinity is a powerful theme which, although it works great for any WordPress site, specializes
	in supporting the BuddyPress plugin and many other BuddyPress related plugins. It can function as a parent
	theme, or can be easily forked to build your own custom theme.
</p>

<p>
	Infinity is the only known theme which natively supports Commons In A Box (CBOX), and in fact,
	the official default CBOX theme is built on top of Infinity. Because of this relationship, no less than
	three BuddyPress core developers have made contributions to Infinity, in addition to several other current
	and former BuddyPress core contributors.
</p>

<?php
	get_template_part( 'templates/dashboard/cpanel-help', get_stylesheet() );
?>

<h3>
	Community
</h3>

<p>
	Join our online community! The authors of Infinity are available to help you get going.
</p>

<ul>
	<li>
		<a target="_blank" href="http://community.presscrew.com/">PressCrew Community Site</a>
	</li>
	<li>
		<a target="_blank" href="https://presscrew.freshdesk.com/support/home">PressCrew Help Desk</a>
	</li>
</ul>

<h3>
	Code Repositories
</h3>

<p>
	The Infinity code is mirrored to GitHub for your convenience.
</p>

<ul>
	<li>
		<a target="_blank" href="https://github.com/PressCrew/infinity">Repository</a>
	</li>
	<li>
		<a target="_blank" href="https://github.com/PressCrew/infinity/issues">Issue Tracker</a>
	</li>
</ul>

<h3>
	Articles (admittedly they are a bit out of date)
</h3>

<p>
	About the authors, and some awesome use cases and primers. Check them out!
</p>

<ul>
	<li>
		<a target="_blank" href="http://community.presscrew.com/developer-introduction/">A Word From the Authors</a>
	</li>
	<li>
		<a target="_blank" href="http://community.presscrew.com/multisite-and-infinity-a-great-combination/">MultiSite and Infinity: A Great Combination</a>
	</li>
	<li>
		<a target="_blank" href="http://community.presscrew.com/buddypress-introduction/">BuddyPress Support Introduction</a>
	</li>
</ul>

<h3>
	60 Second Primer (for the impatient hacker)
</h3>

<ul>
	<li>The first rule of Infinity, don't touch Infinity itself! (Unless you are just playing around)</li>
	<li>Always extend Infinity with a child theme, and activate that child theme.</li>
	<li>Create the identical path/to/file to extend and/or override it.</li>
	<li>Don't get caught up in the boring templates. They are built for flexibility, not fancy (that is your job).</li>
	<li>Infinity is extrememly powerful. It does more than you can imagine. Don't bail early.</li>
</ul>

<h3>Features that work NOW</h3>

<p>
	Unless otherwise noted, none of the features require any PHP code to be written.
</p>

<blockquote>
	This is not a complete list. Always refer to the documentation for the full monty.
</blockquote>

<ul>
	<li>
		Auto script and style enqueueing
		<ul>
			<li>Define handles globally and enqueue as needed</li>
			<li>Enqueue files based on standard or custom conditions</li>
			<li>Enqueue files on specific actions</li>
			<li>Easily override jQuery UI theme with your own</li>
		</ul>
	</li>
	<li>
		Instant theme options panel
		<ul>
			<li>Dozens of built in option types to choose from</li>
			<li>Add new options via a config file.</li>
			<li>Write custom option types (req PHP)</li>
			<li>Organize options by sections, supports infinite hierarchy</li>
			<li>Link options together to form a sibling set</li>
			<li>Show options only if a feature is supported</li>
			<li>Show options only if user has specified capabilities</li>
			<li>Child themes can easily override most option settings of any parent theme (title, description, default value, etc)</li>
		</ul>
	</li>
	<li>
		Features (add_theme_support())
		<ul>
			<li>Custom CSS</li>
			<li>Custom header logo</li>
			<li>Custom header background</li>
			<li>Custom site background</li>
			<li>Gravatar power tool</li>
			<li>Design simple custom features by combining options with feature templates</li>
			<li>Write your own advanced custom features (req PHP)</li>
		</ul>
	</li>
	<li>
		Extending Infinity
		<ul>
			<li>Extend using a child theme</li>
			<li>Extend by rolling your own base theme (req advanced PHP skills)</li>
		</ul>
	</li>
</ul>
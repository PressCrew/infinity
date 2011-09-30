<div class="infinity-docs">

	<h2>Welcome to Infinity</h2>

	<p>
		We can't tell you how happy we are to finally show a select group of people
		from the WordPress Community what we've been working on for the last year.
		What started out small, has quickly evolved into something much bigger,
		something we are very proud of. We hope that you feel the same after trying
		Infinity yourself.
	</p>

	<h3>
		Documentation
	</h3>

	<p>
		The theme developer documentation is available right in the theme, as well as online!
		Its the best place to start.
		<ul>
			<li>
				<a target="infinity-cpanel-tab-ddocs" href="<?php print infinity_screens_route( 'cpanel', 'ddocs', 'index' ) ?>">Built-In Docs &raquo;</a>
			</li>
			<li>
				<a target="_blank" href="http://infinity.presscrew.com/docs">Online Docs &raquo;</a>
			</li>
			<li>
				<a target="_blank" href="http://infinity.presscrew.com/api">Online API Manual &raquo;</a>
			</li>
		</ul>
	</p>

	<h3>
		Community & Support
	</h3>

	<p>
		Join our online community! The authors of Infinity are available to help you get going.
	</p>

	<ul>
		<li>
			<a target="_blank" href="http://infinity.presscrew.com/api">PressCrew Community Site &raquo;</a>
		</li>
		<li>
			<a target="_blank" href="http://community.presscrew.com/discussions/">Discussion Forums &raquo;</a>
		</li>
	</ul>

	<h3>
		Infinity Articles
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
		<li>The best place to start poking around is the config/ dir.</li>
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
			Infinite child themes with cascading configurations
			<ul>
				<li>Cascading configurations</li>
				<li>Cascading templates</li>
				<li>Cascading assets (images, styles, scripts)</li>
				<li>Cascading options</li>
			</ul>
		</li>
		<li>
			Fully customizable control panel
			<ul>
				<li>Themable with jQuery UI theme roller</li>
				<li>Tab based browsing</li>
				<li>Add new control panel screens in seconds via config file</li>
				<li>Full control over content via templates which you define</li>
				<li>Automatic menu, supports infinite hierarchy</li>
				<li>Write documentation quickly and easily using Markdown, Textile, or HTML</li>
			</ul>
		</li>
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
				<li>Easily link documentation to an option</li>
				<li>Easily override documentation defined by any parent theme</li>
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
			Shortcodes
			<ul>
				<li>Define custom shortcodes based on re-usable types</li>
				<li>Define multiple shortcodes which execute the same callback but have different default attributes</li>
				<li>Write custom shortcode types (req PHP)</li>
				<li>Easily override a shortcode's settings that were defined by any parent theme (title, description, etc)</li>
				<li>Easily override a shortcode's default attributes that were set by any parent theme</li>
				<li>Easily override a shortcode's template that were set by any parent theme (override file, or location)</li>
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
</div>
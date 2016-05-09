<header>
	<h1><a href="/" title="Home">The Event Day</a></h1>
	{if isset($button)}
	<a class="button" id="{$button|lower}" href="/{$button|lower}/">{$button}</a>
	{/if}
	{if $logged_in}
	<a class="button" id="back" href="/events/">Events</a>
	{/if}
</header>

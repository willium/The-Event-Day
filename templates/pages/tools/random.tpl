{if isset($attendees)}
<ol id="table">
	{foreach $attendees as $attendee}
	{$a = $attendee->attendee}
	{if $count > count($attendees)}{$count = count($attendees)}{/if}
	<li class="item {if $attendee@index < $count}highlight {if $attendee@index eq 0}first{/if} {if $attendee@index eq $count-1}last{/if}{/if}">{$a->first_name} {$a->last_name}</li>
	{/foreach}
</ol>
<div id="bottom">
	<form action="" method="POST">
		<input type="text" class="text center" name="count" placeholder="number of attendees"/>
		<input type="submit" class="button g" name="submit" value="Generate Again"/>
	</form>
</div>
{else}
<div id="middle">
	<form action="" method="POST">
		<h2>Generate</h2>
		<input type="text" class="text center" name="count" placeholder="number of attendees"/>
		<input type="submit" class="button g" name="submit" value="Generate"/>
	</form>
</div>
{/if}
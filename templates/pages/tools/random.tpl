{if isset($attendees) && is_array($attendees)}
  {if count($attendees) > 0}
    <ol id="table">
    {foreach $attendees as $attendee}
	<li class="item {if $attendee@index < $count}highlight 
                      {if $attendee@index eq 0}first{/if}
                      {if $attendee@index eq $count-1}last{/if}
                    {/if}">{$attendee->attendee->first_name} {$attendee->attendee->last_name}</li>
	{/foreach}
    </ol>
  {else}
    <p class="error">Sorry, no attendees were found for this event. Need to check in attendees?</p>
  {/if}
<div id="bottom">
{else}
<div id="middle">
{/if}
	<form action="" method="POST">
		<h2>Generate</h2>
		<input type="text" class="text center" name="count" placeholder="number of attendees"/>
		<input type="submit" class="button g" name="submit" value="Generate"/>
	</form>
</div>

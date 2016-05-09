{if isset($teams)}
  {if $teams == false || !is_array($teams) || (is_array($teams) && (count($teams) == 0 )) }
  <p class="error">Sorry, you will need to check in more attendees in order to form teams for this Event.</p>
  {else}
  <ol id="table">
	{foreach $teams as $team}
	<li class="item team {if $team@iteration is odd by 1}highlight{/if}">
		<ul>
		{foreach $team as $attendee}
		{$a = $attendee->attendee}
			<li class="item">
				{$a->first_name} {$a->last_name}
			</li>
		{/foreach}
		</ul>
	</li>
	{/foreach}
  </ol>
  {/if}
<div id="bottom">
{else}
<div id="middle">
{/if}
    <h2>Make Teams</h2>
	<form action="" method="POST">
		<div class="row">
			<input type="text" class="text small left" name="teams" placeholder="# of teams"/>
			<label class="label"> team(s) of </label>
			<input type="text" class="text small right" name="of" placeholder="people per team"/>
		</div>
		<input type="submit" class="button g" name="submit" value="Generate"/>
	</form>
</div>

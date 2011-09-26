{if isset($teams)}
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
<div id="bottom">
	<form action="" method="POST">
		<div class="row">
			<input type="text" class="text small left" name="teams" placeholder="# of teams"/>
			<label class="label"> team(s) of </label>
			<input type="text" class="text small right" name="of" placeholder="people per team"/>
		</div>
		<input type="submit" class="button g" name="submit" value="Generate Again"/>
	</form>
</div>
{else}
<div id="middle">
	<form action="" method="POST">
		<h2>Make Teams</h2>
		<div class="row">
			<input type="text" class="text small left" name="teams" placeholder="# of teams"/>
			<label class="label"> team(s) of </label>
			<input type="text" class="text small right" name="of" placeholder="people per team"/>
		</div>
		<input type="submit" class="button g" name="submit" value="Generate"/>
	</form>
</div>
{/if}
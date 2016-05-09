<ul id="list">
{if is_array($events) && count($events) > 0 }
{foreach $events as $event}
	<li class="item"><span class="name">{$event->event->title}</span><a href="/event/{$event->event->id}/" class="button g">Open</a></li>
{/foreach}
{else}
  <li class="item"><span class="name">No live events were found for this user.</span></li>
{/if}
</ul>

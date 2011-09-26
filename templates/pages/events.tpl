<ul id="list">
{foreach $events as $event}
	<li class="item"><span class="name">{$event[0]->event->title}</span><a href="http://theeventday.com/event/{$event[0]->event->id}/" class="button g">Open</a></li>
{/foreach}
</ul>
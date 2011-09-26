<ul id="list">
{foreach $tools as $key=>$value}
	<li class="item"><span class="name">{$key}</span><a href="{$value}" class="button g">Use</a></li>
{/foreach}
</ul>
<div id="home">
<h2>Eventbrite Account Access</h2>
    {if isset( $user_email ) && isset( $user_name ) }
    <div class="block" style='float:none;text-align:center;margin-left:auto;margin-right:auto;'>
        <h3>Welcome Back</h3>
        <p>You are logged in as:<br/>{$user_name}<br/><i>({$user_email})</i>
</p>
    <a style="float:none;" class="button" href="/logout">Logout</a>
    </div>
    <a id="action" style="float:none;" class="button" href="/events/">Select an Event to Manage</a>
    {else}
    <div class="block" style='float:none;text-align:center;margin-left:auto;margin-right:auto;'>
        <h3>Authenticate with Eventbrite</h3>
        <p>In order to help you manage your events, we will need access to your <a href="http://eventbrite.com">Eventbrite</a> account data.</p>
        <a style="float:none;" class="button" href="{$oauth_link}">Connect to Eventbrite</a>
    </div>
	<a style="float:none;" id="action" class="button" href="{$oauth_link}">Connect with your Eventbrite account</a>
    {/if}
</div>

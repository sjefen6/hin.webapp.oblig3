<!DOCTYPE html>
<html>
<head>
<title>{$title}</title>
</head>
<body>
	<header>
		<hgroup>
			<h1>{$title}</h1>
			<h2>{$subtitle}</h2>
		</hgroup>
	</header>
	<nav>
{foreach from=$menu item=i}
		<a href="{$i.url}">{$i.name}</a>
{/foreach}
{if $failed}
	<span>Loggon failed!</span>
{/if}
{if $signedIn}
	<span>You are signed in</span>
{else}
	<form action="?login=in" method="post">
		<label for="userId">Un:</label>
		<input type="text" name="userId" />
		<label for="password">Pw:</label>
		<input type="password" name="password" />
		<input type="submit" value="Sign In" />
	</form>
{/if}
	</nav>
{if $mode eq 'bloglist'}
	<section id="articles">
		{foreach from=$articles item=article}
		<article>
			<a href="?post={$article.id}"><h1>{$article.title}</h1></a>
			<section class="articleContent">
			{$article.desc}
			</section>
		</article>
		{/foreach}
	</section>
{elseif $mode eq 'post'}
	<section id="articles">
		<article>
			<h1>{$post.title}</h1>
			<section class="articleContent">
			{$post.desc}
			</section>
		</article>
	</section>
{elseif $mode eq 'page'}
	<section id="articles">
		<article>
			<h1>{$page.title}</h1>
			<section class="articleContent">
			{$page.desc}
			</section>
		</article>
	</section>
{else}
	<section id="articles">
		<article>
			<h1>404 Not Found</h1>
			<section class="articleContent">
			Ikke funnet.
			</section>
		</article>
	</section>
{/if}
</body>
</html>
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$title}</title>
	<link rel="stylesheet" type="text/css" href="{$subdir}../style/main.css">
	<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>
</head>
<body>
<div id="crumbs">
    &uarr;<a href="..">UP</a>&nbsp;&nbsp;
    {if $package}
	<span>
	Package: <a href="{$subdir}elementindex_{$package}.html" class="menu">{$package}</a>  
	{if $subpackage}
	    /{$subpackage}
    {/if}&nbsp;&nbsp;
	</span>
		  [ <a href="{$subdir}classtrees_{$package}.html" class="menu">class tree</a> ]
	{/if}
    <span class="crumbs_right">
		{if $hastodos}
				<span id="todolist">
				    [ <a href="{$subdir}{$todolink}">Todos</a> ]
				</span>
		{/if}
		  [ <a href="{$subdir}elementindex.html" class="menu">all elements</a> ]
    </span>
</div>

<div id="sitebar">
	{if count($ric) >= 1}
		<div class="package">
			<div id="ric">
				{section name=ric loop=$ric}
					<p><a href="{$subdir}{$ric[ric].file}">{$ric[ric].name}</a></p>
				{/section}
			</div>
		</div>
	{/if}
	{if $tutorials}
		<b>Tutorials/Manuals:</b><br />
		<div class="package">
			{if $tutorials.pkg}
				<strong>Package-level:</strong>
				{section name=ext loop=$tutorials.pkg}
					{$tutorials.pkg[ext]}
				{/section}
			{/if}
			{if $tutorials.cls}
				<strong>Class-level:</strong>
				{section name=ext loop=$tutorials.cls}
					{$tutorials.cls[ext]}
				{/section}
			{/if}
			{if $tutorials.proc}
				<strong>Procedural-level:</strong>
				{section name=ext loop=$tutorials.proc}
					{$tutorials.proc[ext]}
				{/section}
			{/if}
		</div>
	{/if}
	{if !$noleftindex}{assign var="noleftindex" value=false}{/if}
	{if !$noleftindex}
		{if $compiledclassindex}
			<b>Classes:</b><br />
			{eval var=$compiledclassindex}
		{/if}
		{if $compiledinterfaceindex}
			<b>Interfaces:</b><br />
			{eval var=$compiledinterfaceindex}
		{/if}
        {*
		{if $compiledfileindex}
			<b>Files:</b><br />
			{eval var=$compiledfileindex}
		{/if}*}
		<br />
	{/if}
</div>

	<div id="packages">
		<div>
		<b>Packages:</b>
		</div>
			{section name=packagelist loop=$packageindex}
				<a href="{$subdir}{$packageindex[packagelist].link}">{$packageindex[packagelist].title}</a><br />
			{/section}
	</div>

<div id="main">
	{if !$hasel}{assign var="hasel" value=false}{/if}
	{if $eltype == 'class' && $is_interface}{assign var="eltype" value="interface"}{/if}
	{if $hasel}
		<h1>{$eltype|capitalize}: {$class_name}</h1>
		Source Location: {$source_location}<br /><br />
	{/if}

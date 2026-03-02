<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="xml" indent="yes" cdata-section-elements="title" encoding="utf-8"/>

<xsl:variable name="lang">
	<xsl:choose>
		<xsl:when test="normalize-space(//SERIAL/CONTROLINFO/LANGUAGE) != ''">
			<xsl:value-of select="//SERIAL/CONTROLINFO/LANGUAGE"/>
		</xsl:when>
		<xsl:when test="normalize-space(//CONTROLINFO/LANGUAGE) != ''">
			<xsl:value-of select="//CONTROLINFO/LANGUAGE"/>
		</xsl:when>
		<xsl:when test="normalize-space(//LANG) != ''">
			<xsl:value-of select="//LANG"/>
		</xsl:when>
		<xsl:otherwise>en</xsl:otherwise>
	</xsl:choose>
</xsl:variable>
<xsl:variable name="server">
	<xsl:choose>
		<xsl:when test="normalize-space(//CONTROLINFO/SCIELO_INFO/SERVER) != ''">
			<xsl:value-of select="//CONTROLINFO/SCIELO_INFO/SERVER"/>
		</xsl:when>
		<xsl:otherwise>scielo-proceedings</xsl:otherwise>
	</xsl:choose>
</xsl:variable>
<xsl:variable name="pagePid">
	<xsl:choose>
		<xsl:when test="normalize-space(//SERIAL/CONTROLINFO/PAGE_PID) != ''">
			<xsl:value-of select="//SERIAL/CONTROLINFO/PAGE_PID"/>
		</xsl:when>
		<xsl:when test="normalize-space(//PID) != ''">
			<xsl:value-of select="//PID"/>
		</xsl:when>
		<xsl:otherwise></xsl:otherwise>
	</xsl:choose>
</xsl:variable>

<xsl:template match="/">
<xsl:element name="rss">
	<xsl:attribute name="version">2.0</xsl:attribute>

	<xsl:element name="channel">
		<xsl:element name="generator">Scielo RSS</xsl:element>
		
			<xsl:element name="title">
				<xsl:choose>
					<xsl:when test="normalize-space(SERIAL/TITLEGROUP/TITLE) != ''">
						<xsl:value-of select="SERIAL/TITLEGROUP/TITLE"/>
					</xsl:when>
					<xsl:when test="normalize-space(//CONTROLINFO/SCIELO_INFO/databases/db[@type='title' and @lang=$lang]) != ''">
						<xsl:value-of select="//CONTROLINFO/SCIELO_INFO/databases/db[@type='title' and @lang=$lang]"/>
					</xsl:when>
					<xsl:otherwise>SciELO Proceedings RSS</xsl:otherwise>
				</xsl:choose>
			</xsl:element>
			
			<xsl:element name="link">http://<xsl:value-of select="$server"/>/rss.php?pid=<xsl:value-of select="$pagePid"/>&amp;lang=<xsl:value-of select="$lang"/></xsl:element>
			
			<xsl:element name="language">
				<xsl:value-of select="$lang" />
			</xsl:element>
		
		<xsl:element name="image">
			<xsl:element name="title">SciELO Logo</xsl:element>
			<xsl:element name="url">http://<xsl:value-of select="$server"/>/img/en/fbpelogp.gif</xsl:element>
			<xsl:element name="link">http://<xsl:value-of select="$server"/></xsl:element>
		</xsl:element>
		<xsl:apply-templates select="SERIAL/CONTROLINFO" />
		<xsl:apply-templates select="SERIAL/ISSUE" />
	</xsl:element>
</xsl:element>
</xsl:template>


<xsl:template match="ISSUE">
	<xsl:for-each select="SECTION">
		<xsl:apply-templates select="ARTICLE" />
	</xsl:for-each>
</xsl:template>

<xsl:template match="CONTROLINFO">
</xsl:template>

<xsl:template match="ARTICLE">
	<xsl:element name="item">
		<xsl:element name="title">
			<xsl:choose>
				<xsl:when test="@TEXT_LANG = $lang"><xsl:value-of select="TITLE" /></xsl:when>
				<xsl:otherwise><xsl:value-of select="TITLE" /></xsl:otherwise>
			</xsl:choose>
		</xsl:element>
		<xsl:element name="link">http://<xsl:value-of select="$server"/>/scielo.php?script=sci_arttext&amp;pid=<xsl:value-of select="@PID" />&amp;lng=<xsl:value-of select="$lang" />&amp;nrm=iso&amp;tlng=<xsl:value-of select="$lang" /></xsl:element>
	</xsl:element>
</xsl:template>

<xsl:template match="STRIP">
</xsl:template>

</xsl:stylesheet>

<?php
$config = array(
	'useShell' => false,
	/*
	 * select the cron mode - options:
	 * - 'all': updates All feeds in a single process
	 * - 'everyone': create a new process for each feed, useful if you are not sure of your sources (feeds) validity
	 */
	'cronMode' => 'everyone',
	'website_url' => 'http://www.gis-cist.fr/rss-geomediamapper/',
	'email_reports' => array(
		'from' => '',
		'subject' => 'CIST - cron alert',
		'to' => array(
			''
		),
		'cc' => array(
			''
		),
		'bcc' => array()
	),
	'feed' => array(
		'types' => array(
			'1' => 'General',
			'2' => 'International', 
			'3' => 'News'
		),
		'languages' => array(
			'en' => 'english',
			'fr' => 'french'
		),
		'update_intervals' => array(
			'60' => '1 minute',
			'600' => '10 minutes',
			'900' => '15 minutes',
			'1800' => '1/2 hour',
			'3600' => '1 hour',
			'43200' => '12 hours',
			'86400' => '1 day',
			'172800' => '2 days',
			'604800' => '1 week',
			'1209600' => '2 weeks',
			'2592000' => '30 days'
		),
		'update_intervals_default' => '900'
	),
	'user' => array(
		//role restrictions
		'roles' => array(
			'admin' => array(),
			'reader' => array(
				'controllers' => array(),
				'actions' => array(
					'add', 'edit', 'delete',
					'admin_add', 'admin_edit', 'admin_delete'
				)
			)
		)
	)
);
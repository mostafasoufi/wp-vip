=== Wordpress VIP ===
Contributors: mostafa.s1990, man4toman, parselearn
Donate link: http://iran98.org/
Tags: wordpress, vip, user, role, payment, download, download per payment, persian, parsi, iran
Requires at least: 4.0
Tested up to: 4.9.8
Stable tag: 1.0.1

Pay per download for VIP user in wordpress

== Description ==
A powerful plugin Pay per download manager for wordpress

Features:

* User management roles
* The cost of credit for the role
* Management users role
* Add user to role
* Management files role
* Add file to role
* Add user payment
* Enable/disable vip user
* Send download link for the user indirectly

Send email for Translation files: mst404[a]gmail[dot].com
for translate, please open langs/default.po by Poedit and translate strings.

== Installation ==
1. Upload `vip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Goto setting page and managment setting.
4. Goto roles user page and create new role.
5. Goto users page and add new user to role.
6. Goto files page and upload a new file to role and get download link for using in post/page.
7. Goto payments page and add new payment for user.
8. Other functions plugin:

* Convert Byte to MB: `<?php echo vip_formatSizeUnits($byte); ?>`
* Get file type: `<?php echo vip_file_type($filename); ?>`
* Get file mime: `<?php echo vip_mime_type($filename); ?>`
* Get user credit: `<?php echo vip_get_credit_by_userid($user_id); ?>`
* Get user payments: `<?php echo vip_get_payments_by_userid($user_id); ?>`
* Get user role: `<?php echo vip_get_user_role($column); ?>`
* Get file role: `<?php echo vip_get_file_role($file_id, $column); ?>`

== Frequently Asked Questions ==
A powerful plugin Pay per download manager for wordpress

== Screenshots ==
1. Screen shot (screenshot-1.png) in Setting page
2. Screen shot (screenshot-2.png) in Roles managment page
3. Screen shot (screenshot-3.png) in Users managment page
4. Screen shot (screenshot-4.png) in Files managment page
5. Screen shot (screenshot-5.png) in Payments managment page

== Upgrade Notice ==
= 1.0 =
* Start plugin

== Changelog ==
= 1.0.1 =
* This is not new version. This is announcement for new version. Be patient...

= 1.0 =
* Start plugin
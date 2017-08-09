# Voter Visibility - SMF "Mod"

**SMF Mod - Last Published Release:**
  - Published Module: https://custom.simplemachines.org/mods/index.php?mod=3373
  - Support Topic: https://www.simplemachines.org/community/index.php?topic=475722.0

This is a modification for Simple Machines Forum (SMF) that enhances polling to allow voters to be displayed for those needing to organize and record the results of polls.  For example, when organizing a group event, friends are often curious about how other friends voted.  Likewise, the secretary of an organization might need to be able to record the results of certain polls (who voted for what).  In addition, some polls might want to allow (or require) comments for certain responses.  With this modification, each poll can be configured to allow various levels of _voter visibility_ and _comment requirements_, and then the _voter log_ can be viewed by those who are allowed to see them based on the configured voter visibility.

**This mod has been well received and well tested in several very active forums.**  Anyone who needs these features cannot imagine life without it.  While I'm not actively supporting it any longer, if you get my attention, I can try to help.

## Main Features
  - Voter Visibility and Comment Requirements can be specified when creating a new poll.
  - Voter Visibility can be set to:
    - **PUBLIC** - Everyone can see the voter log for the poll.
    - **DISCREET** - Only the creator and admins can see the voter log for the poll.
    - **PRIVATE** - Only admins can see the voter log for the poll.
  - Comments can be optional or required depending upon the vote choice or option selected.

## Other Notes
  - This mod is most likely **not compatible with other mods** that significantly change poll behavior (e.g., multiple polls mods).
  - **Visibility cannot be changed once the poll is created** because that is too sensitive an operation.  Members may vote differently if they know their vote will be PUBLIC versus PRIVATE and changing visibility without their consent would be a violation of the trust communicated when they first voted.
  - Guests that are allowed to post polls cannot specify DISCREET voter visibility because there is no originating account associated with the poll.
  - When comments are available, there is only one comment block available for the vote because that is sufficient most users.
  - The Vote Log results table is a javascript sortable table created by Christian Bach and known as [tablesorter 2.0](http://tablesorter.com/).

## Release Notes
  - **1.00** - The initial release candidate.[/li]
  - **1.01** - Small bug fixes discovered during the mod approval process.
    - Addressed feedback provided by the mod approval team.
    - Fixed a bug relating to guest voting features.
  - **1.02** - Bugs fixes for issues reported by users in the support forum.
    - Fixed errors being reported relating to polls that do not allow comments.
    - Fixed add polls so that the visibility can be set when adding a poll to an existing topic.
    - Upgraded tablesorter and jQuery to latest stable releases.
  - **2.0** - Redefining version labeling scheme so package manager can see the updates.
  - **2.1** - Updated to be compatible with latest SMF and other minor updates.
    - Added support for 2.0.9-2.0.11 (only verified against 2.0.11).
    - Updated jQuery library to latest stable release.
    - Corrected visibility label from "DISCRETE" to "DISCREET".
    - Changed default visibility level to PUBLIC based on user feedback.
  - **2.2** - Released as GNU GPLv3 open source and published on GitHub.

## License agreement
  - This modification was created by **Ray Cardillo** of [Cardillo's Creations](https://CardillosCreations.com)
  - By using this software, you agree to be bound by the license agreement.
  - A copy of the license is included in the [b]license.txt[/b] file at the root of the installation package.
  - Simple Machines<sup>®</sup> is authorized to distribute this modification on SimpleMachines.org and any other medium.

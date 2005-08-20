#!/usr/bin/perl -w
eval 'exec perl -S $0 "$@"'
    if 0;

=head1 NAME

pflogsumm.pl - Produce Postfix MTA logfile summary

Copyright (C) 1998-2003 by James S. Seymour, Release 1.0.10.

=head1 SYNOPSIS

    pflogsumm.pl -[eq] [-d <today|yesterday>] [-h <cnt>] [-u <cnt>]
	[--verp_mung[=<n>]] [--verbose_msg_detail] [--iso_date_time]
	[-m|--uucp_mung] [-i|--ignore_case] [--smtpd_stats] [--mailq]
	[--problems_first] [--rej_add_from] [--no_bounce_detail]
	[--no_deferral_detail] [--no_reject_detail] [--zero_fill]
	[--syslog_name=string] [file1 [filen]]

    pflogsumm.pl -[help|version]

    If no file(s) specified, reads from stdin.  Output is to stdout.

=head1 DESCRIPTION

    Pflogsumm is a log analyzer/summarizer for the Postfix MTA.  It is
    designed to provide an over-view of Postfix activity, with just enough
    detail to give the administrator a "heads up" for potential trouble
    spots.
    
    Pflogsumm generates summaries and, in some cases, detailed reports of
    mail server traffic volumes, rejected and bounced email, and server
    warnings, errors and panics.

=head1 OPTIONS

    -d today       generate report for just today
    -d yesterday   generate report for just "yesterday"

    -e             extended (extreme? excessive?) detail

		   Emit detailed reports.  At present, this includes
		   only a per-message report, sorted by sender domain,
		   then user-in-domain, then by queue i.d.

                   WARNING: the data built to generate this report can
                   quickly consume very large amounts of memory if a
		   lot of log entries are processed!

    -h <cnt>       top <cnt> to display in host/domain reports.
    
		   0 = none.

                   See also: "-u" and "--no_*_detail" for further
			     report-limiting options.

    --help         Emit short usage message and bail out.
    
		   (By happy coincidence, "-h" alone does much the same,
		   being as it requires a numeric argument :-).  Yeah, I
		   know: lame.)

    -i
    --ignore_case  Handle complete email address in a case-insensitive
                   manner.
		   
		   Normally pflogsumm lower-cases only the host and
		   domain parts, leaving the user part alone.  This
		   option causes the entire email address to be lower-
		   cased.

    --iso_date_time

                   For summaries that contain date or time information,
		   use ISO 8601 standard formats (CCYY-MM-DD and HH:MM),
		   rather than "Mon DD CCYY" and "HHMM".

    -m             modify (mung?) UUCP-style bang-paths
    --uucp_mung

                   This is for use when you have a mix of Internet-style
                   domain addresses and UUCP-style bang-paths in the log.
                   Upstream UUCP feeds sometimes mung Internet domain
                   style address into bang-paths.  This option can
                   sometimes undo the "damage".  For example:
                   "somehost.dom!username@foo" (where "foo" is the next
                   host upstream and "somehost.dom" was whence the email
                   originated) will get converted to
                   "foo!username@somehost.dom".  This also affects the
                   extended detail report (-e), to help ensure that by-
                    domain-by-name sorting is more accurate.

    --mailq        Run "mailq" command at end of report.
    
		   Merely a convenience feature.  (Assumes that "mailq"
		   is in $PATH.  See "$mailqCmd" variable to path thisi
		   if desired.)

    --no_bounce_detail
    --no_deferral_detail
    --no_reject_detail

                   Suppresses the printing of the following detailed
                   reports, respectively:

			message bounce detail (by relay)
			message deferral detail
			message reject detail

                   See also: "-u" and "-h" for further report-limiting
                             options.

    --problems_first

                   Emit "problems" reports (bounces, defers, warnings,
		   etc.) before "normal" stats.

    --rej_add_from
                   For those reject reports that list IP addresses or
                   host/domain names: append the email from address to
                   each listing.  (Does not apply to "Improper use of
		   SMTP command pipelining" report.)

    -q             quiet - don't print headings for empty reports
    
		   note: headings for warning, fatal, and "master"
		   messages will always be printed.

    --smtpd_stats

                   Generate smtpd connection statistics.

                   The "per-day" report is not generated for single-day
                   reports.  For multiple-day reports: "per-hour" numbers
                   are daily averages (reflected in the report heading).

    --syslog_name=name

		   Set syslog_name to look for for Postfix log entries.

		   By default, pflogsumm looks for entries in logfiles
		   with a syslog name of "postfix," the default.
		   If you've set a non-default "syslog_name" parameter
		   in your Postfix configuration, use this option to
		   tell pflogsumm what that is.

    -u <cnt>       top <cnt> to display in user reports. 0 == none.

                   See also: "-h" and "--no_*_detail" for further
			     report-limiting options.

    --verbose_msg_detail

                   For the message deferral, bounce and reject summaries:
                   display the full "reason", rather than a truncated one.

                   Note: this can result in quite long lines in the report.

    --verp_mung    do "VERP" generated address (?) munging.  Convert
    --verp_mung=2  sender addresses of the form
                   "list-return-NN-someuser=some.dom@host.sender.dom"
                    to
                      "list-return-ID-someuser=some.dom@host.sender.dom"

                    In other words: replace the numeric value with "ID".

                   By specifying the optional "=2" (second form), the
                   munging is more "aggressive", converting the address
                   to something like:

                        "list-return@host.sender.dom"

                   Actually: specifying anything less than 2 does the
                   "simple" munging and anything greater than 1 results
                   in the more "aggressive" hack being applied.

		   See "NOTES" regarding this option.

    --version      Print program name and version and bail out.

    --zero_fill    "Zero-fill" certain arrays so reports come out with
                   data in columns that that might otherwise be blank.

=head1 RETURN VALUE

    Pflogsumm doesn't return anything of interest to the shell.

=head1 ERRORS

    Error messages are emitted to stderr.

=head1 EXAMPLES

    Produce a report of previous day's activities:

        pflogsumm.pl -d yesterday /var/log/maillog

    A report of prior week's activities (after logs rotated):

        pflogsumm.pl /var/log/maillog.0

    What's happened so far today:

        pflogsumm.pl -d today /var/log/maillog

    Crontab entry to generate a report of the previous day's activity
    at 10 minutes after midnight.

	10 0 * * * /usr/local/sbin/pflogsumm -d yesterday /var/log/maillog
	2>&1 |/usr/bin/mailx -s "`uname -n` daily mail stats" postmaster

    Crontab entry to generate a report for the prior week's activity.
    (This example assumes one rotates ones mail logs weekly, some time
    before 4:10 a.m. on Sunday.)

	10 4 * * 0   /usr/local/sbin/pflogsumm /var/log/maillog.0
	2>&1 |/usr/bin/mailx -s "`uname -n` weekly mail stats" postmaster

    The two crontab examples, above, must actually be a single line
    each.  They're broken-up into two-or-more lines due to page
    formatting issues.

=head1 SEE ALSO

    The pflogsumm FAQ: pflogsumm-faq.txt.

=head1 NOTES

    Pflogsumm makes no attempt to catch/parse non-Postfix log
    entries.  Unless it has "postfix/" in the log entry, it will be
    ignored.

    It's important that the logs are presented to pflogsumm in
    chronological order so that message sizes are available when
    needed.

    For display purposes: integer values are munged into "kilo" and
    "mega" notation as they exceed certain values.  I chose the
    admittedly arbitrary boundaries of 512k and 512m as the points at
    which to do this--my thinking being 512x was the largest number
    (of digits) that most folks can comfortably grok at-a-glance.
    These are "computer" "k" and "m", not 1000 and 1,000,000.  You
    can easily change all of this with some constants near the
    beginning of the program.

    "Items-per-day" reports are not generated for single-day
    reports.  For multiple-day reports: "Items-per-hour" numbers are
    daily averages (reflected in the report headings).

    Message rejects, reject warnings, holds and discards are all
    reported under the "rejects" column for the Per-Hour and Per-Day
    traffic summaries.

    Verp munging may not always result in correct address and
    address-count reduction.

    Verp munging is always in a state of experimentation.  The use
    of this option may result in inaccurate statistics with regards
    to the "senders" count.

    UUCP-style bang-path handling needs more work.  Particularly if
    Postfix is not being run with "swap_bangpath = yes" and/or *is* being
    run with "append_dot_mydomain = yes", the detailed by-message report
    may not be sorted correctly by-domain-by-user.  (Also depends on
    upstream MTA, I suspect.)

    The "percent rejected" and "percent discarded" figures are only
    approximations.  They are calculated as follows (example is for
    "percent rejected"):

	percent rejected =
	
	    (rejected / (delivered + rejected + discarded)) * 100


    The Pflogsumm Home Page is at:

	http://jimsun.LinxNet.com/postfix_contrib.html

=head1 REQUIREMENTS

    Pflogsumm requires the Date::Calc module, which can be obtained from
    CPAN at http://www.perl.com.

    Pflogsumm is currently written and tested under Perl 5.005_03.
    As of version 19990413-02, pflogsumm worked with Perl 5.003, but
    future compatibility is not guaranteed.

=head1 LICENSE

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You may have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,
    USA.
    
    An on-line copy of the GNU General Public License can be found
    http://www.fsf.org/copyleft/gpl.html.

=cut

use strict;
use locale;
use Getopt::Long;
# ---Begin: SMTPD_STATS_SUPPORT---
use Date::Calc qw(Delta_DHMS);
# ---End: SMTPD_STATS_SUPPORT---

my $mailqCmd = "mailq";
my $release = "1.0.10";

# Variables and constants used throughout pflogsumm
use vars qw(
    $progName
    $usageMsg
    %opts
    $divByOneKAt $divByOneMegAt $oneK $oneMeg
    @monthNames %monthNums $thisYr $thisMon
    $msgCntI $msgSizeI $msgDfrsI $msgDlyAvgI $msgDlyMaxI
    $isoDateTime
);

# Some constants used by display routines.  I arbitrarily chose to
# display in kilobytes and megabytes at the 512k and 512m boundaries,
# respectively.  Season to taste.
$divByOneKAt   = 524288;	# 512k
$divByOneMegAt = 536870912;	# 512m
$oneK          = 1024;		# 1k
$oneMeg        = 1048576;	# 1m

# Constants used throughout pflogsumm
@monthNames = qw(Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec);
%monthNums = qw(
    Jan  0 Feb  1 Mar  2 Apr  3 May  4 Jun  5
    Jul  6 Aug  7 Sep  8 Oct  9 Nov 10 Dec 11);
($thisMon, $thisYr) = (localtime(time()))[4,5];
$thisYr += 1900;

#
# Variables used only in main loop
#
# Per-user data
my (%recipUser, $recipUserCnt);
my (%sendgUser, $sendgUserCnt);
# Per-domain data
my (%recipDom, $recipDomCnt);	# recipient domain data
my (%sendgDom, $sendgDomCnt);	# sending domain data
# Indexes for arrays in above
$msgCntI    = 0;	# message count
$msgSizeI   = 1;	# total messages size
$msgDfrsI   = 2;	# number of defers
$msgDlyAvgI = 3;	# total of delays (used for averaging)
$msgDlyMaxI = 4;	# max delay

my (
    $cmd, $qid, $addr, $size, $relay, $status, $delay,
    $dateStr,
    %panics, %fatals, %warnings, %masterMsgs,
    %msgSizes,
    %deferred, %bounced,
    %noMsgSize, %msgDetail,
    $msgsRcvd, $msgsDlvrd, $sizeRcvd, $sizeDlvrd,
    $msgMonStr, $msgMon, $msgDay, $msgTimeStr, $msgHr, $msgMin, $msgSec,
    $msgYr,
    $revMsgDateStr, $dayCnt, %msgsPerDay,
    %rejects, $msgsRjctd,
    %warns, $msgsWrnd,
    %discards, $msgsDscrdd,
    %holds, $msgsHld,
    %rcvdMsg, $msgsFwdd, $msgsBncd,
    $msgsDfrdCnt, $msgsDfrd, %msgDfrdFlgs,
    %connTime, %smtpdPerDay, %smtpdPerDom, $smtpdConnCnt, $smtpdTotTime,
    %smtpMsgs
);
$dayCnt = $smtpdConnCnt = $smtpdTotTime = 0;

# Init total messages delivered, rejected, and discarded
$msgsDlvrd = $msgsRjctd = $msgsDscrdd = 0;

# Messages received and delivered per hour
my @rcvPerHr = qw(0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0);
my @dlvPerHr = @rcvPerHr;
my @dfrPerHr = @rcvPerHr;	# defers per hour
my @bncPerHr = @rcvPerHr;	# bounces per hour
my @rejPerHr = @rcvPerHr;	# rejects per hour
my $lastMsgDay = 0;

# "doubly-sub-scripted array: cnt, total and max time per-hour
# Gag - some things, Perl doesn't do well :-(
my @smtpdPerHr;
$smtpdPerHr[0]  = [0,0,0]; $smtpdPerHr[1]  = [0,0,0]; $smtpdPerHr[2]  = [0,0,0];
$smtpdPerHr[3]  = [0,0,0]; $smtpdPerHr[4]  = [0,0,0]; $smtpdPerHr[5]  = [0,0,0];
$smtpdPerHr[6]  = [0,0,0]; $smtpdPerHr[7]  = [0,0,0]; $smtpdPerHr[8]  = [0,0,0];
$smtpdPerHr[9]  = [0,0,0]; $smtpdPerHr[10] = [0,0,0]; $smtpdPerHr[11] = [0,0,0];
$smtpdPerHr[12] = [0,0,0]; $smtpdPerHr[13] = [0,0,0]; $smtpdPerHr[14] = [0,0,0];
$smtpdPerHr[15] = [0,0,0]; $smtpdPerHr[16] = [0,0,0]; $smtpdPerHr[17] = [0,0,0];
$smtpdPerHr[18] = [0,0,0]; $smtpdPerHr[19] = [0,0,0]; $smtpdPerHr[20] = [0,0,0];
$smtpdPerHr[21] = [0,0,0]; $smtpdPerHr[22] = [0,0,0]; $smtpdPerHr[23] = [0,0,0];

$progName = "pflogsumm.pl";
$usageMsg =
    "usage: $progName -[eq] [-d <today|yesterday>] [-h <cnt>] [-u <cnt>]
       [--verp_mung[=<n>]] [--verbose_msg_detail] [--iso_date_time]
       [-m|--uucp_mung] [-i|--ignore_case] [--smtpd_stats] [--mailq]
       [--problems_first] [--rej_add_from] [--no_bounce_detail]
       [--no_deferral_detail] [--no_reject_detail] [--zero_fill]
       [--syslog_name=name] [file1 [filen]]

       $progName --[version|help]";

# Some pre-inits for convenience
$isoDateTime = 0;	# Don't use ISO date/time formats
GetOptions(
    "d=s"                => \$opts{'d'},
    "e"                  => \$opts{'e'},
    "help"               => \$opts{'help'},
    "h=i"                => \$opts{'h'},
    "i"                  => \$opts{'i'},
    "ignore_case"        => \$opts{'i'},
    "iso_date_time"      => \$isoDateTime,
    "m"                  => \$opts{'m'},
    "uucp_mung"          => \$opts{'m'},
    "mailq"              => \$opts{'mailq'},
    "no_bounce_detail"   => \$opts{'noBounceDetail'},
    "no_deferral_detail" => \$opts{'noDeferralDetail'},
    "no_reject_detail"   => \$opts{'noRejectDetail'},
    "problems_first"     => \$opts{'pf'},
    "q"                  => \$opts{'q'},
    "rej_add_from"       => \$opts{'rejAddFrom'},
    "smtpd_stats"        => \$opts{'smtpdStats'},
    "syslog_name=s"      => \$opts{'syslogName'},
    "u=i"                => \$opts{'u'},
    "verbose_msg_detail" => \$opts{'verbMsgDetail'},
    "verp_mung:i"        => \$opts{'verpMung'},
    "version"            => \$opts{'version'},
    "zero_fill"          => \$opts{'zeroFill'}
) || die "$usageMsg\n";

# internally: 0 == none, undefined == -1 == all
$opts{'h'} = -1 unless(defined($opts{'h'}));
$opts{'u'} = -1 unless(defined($opts{'u'}));
my $syslogName = $opts{'syslogName'}? $opts{'syslogName'} : "postfix";

if(defined($opts{'help'})) {
    print "$usageMsg\n";
    exit 0;
}

if(defined($opts{'version'})) {
    print "$progName $release\n";
    exit 0;
}

$dateStr = get_datestr($opts{'d'}) if(defined($opts{'d'}));

# debugging
#open(UNPROCD, "> unprocessed") ||
#    die "couldn't open \"unprocessed\": $!\n";

while(<>) {
    next if(defined($dateStr) && ! /^$dateStr/o);
    s/: \[ID \d+\s+[^\]]+\] /: /o;	# lose "[ID nnnnnn some.thing]" stuff
    unless((($msgMonStr, $msgDay, $msgTimeStr, $cmd, $qid) =
	m#^(...)\s+(\d+)\s(..:..:..)\s\S+\s(?:vmailer|$syslogName)[-/]([^\[:]*).*?: ([^:]+)#o) == 5)
    {
	unless((($msgMonStr, $msgDay, $msgTimeStr, $cmd, $qid) =
	    m#^(...)\s+(\d+)\s(..:..:..)\s\S+\s(vmailer|$syslogName[^\[:]*).*?: ([^:]+)#o) == 5)
	{
#		print UNPROCD "$_";
		next;
	}
    }
    chomp;

    # snatch out log entry date & time
    ($msgHr, $msgMin, $msgSec) = split(/:/, $msgTimeStr);
    $msgMon = $monthNums{$msgMonStr};
    $msgYr = $thisYr; --$msgYr if($msgMon > $thisMon);

    # the following test depends on one getting more than one message a
    # month--or at least that successive messages don't arrive on the
    # same month-day in successive months :-)
    unless($msgDay == $lastMsgDay) {
	$lastMsgDay = $msgDay;
	$revMsgDateStr = sprintf "%d%02d%02d", $msgYr, $msgMon, $msgDay;
	++$dayCnt;
	if(defined($opts{'zeroFill'})) {
	    ${$msgsPerDay{$revMsgDateStr}}[4] = 0;
	}
    }

    # regexp rejects happen in "cleanup"
    if((my($rejTyp, $rejSubTyp, $rejReas, $rejRmdr) =
	/^.*\/(cleanup)\[.*?\b(reject|warning|hold|discard): (header|body) (.*)$/o) == 4)
    {
	$rejRmdr =~ s/( from \S+?)?; from=<.*$//o unless($opts{'verbMsgDetail'});
	$rejRmdr = string_trimmer($rejRmdr, 64, $opts{'verbMsgDetail'});
	if($rejSubTyp eq "reject") {
	    ++$rejects{$rejTyp}{$rejReas}{$rejRmdr};
	    ++$msgsRjctd;
	} elsif($rejSubTyp eq "warning") {
	    ++$warns{$rejTyp}{$rejReas}{$rejRmdr};
	    ++$msgsWrnd;
	} elsif($rejSubTyp eq "hold") {
	    ++$holds{$rejTyp}{$rejReas}{$rejRmdr};
	    ++$msgsHld;
	} elsif($rejSubTyp eq "discard") {
	    ++$discards{$rejTyp}{$rejReas}{$rejRmdr};
	    ++$msgsDscrdd;
	}
	++$rejPerHr[$msgHr];
	++${$msgsPerDay{$revMsgDateStr}}[4];
    } elsif($qid eq 'warning') {
	(my $warnReas = $_) =~ s/^.*warning: //o;
	$warnReas = string_trimmer($warnReas, 66, $opts{'verbMsgDetail'});
	++$warnings{$cmd}{$warnReas};
    } elsif($qid eq 'fatal') {
	(my $fatalReas = $_) =~ s/^.*fatal: //o;
	$fatalReas = string_trimmer($fatalReas, 66, $opts{'verbMsgDetail'});
	++$fatals{$cmd}{$fatalReas};
    } elsif($qid eq 'panic') {
	(my $panicReas = $_) =~ s/^.*panic: //o;
	$panicReas = string_trimmer($panicReas, 66, $opts{'verbMsgDetail'});
	++$panics{$cmd}{$panicReas};
    } elsif($qid eq 'reject') {
	proc_smtpd_reject($_, \%rejects, \$msgsRjctd, \$rejPerHr[$msgHr],
			  \${$msgsPerDay{$revMsgDateStr}}[4]);
    } elsif($qid eq 'reject_warning') {
	proc_smtpd_reject($_, \%warns, \$msgsWrnd, \$rejPerHr[$msgHr],
			  \${$msgsPerDay{$revMsgDateStr}}[4]);
    } elsif($qid eq 'hold') {
	proc_smtpd_reject($_, \%holds, \$msgsHld, \$rejPerHr[$msgHr],
			  \${$msgsPerDay{$revMsgDateStr}}[4]);
    } elsif($qid eq 'discard') {
	proc_smtpd_reject($_, \%discards, \$msgsDscrdd, \$rejPerHr[$msgHr],
			  \${$msgsPerDay{$revMsgDateStr}}[4]);
    } elsif($cmd eq 'master') {
	++$masterMsgs{(split(/^.*master.*: /))[1]};
    } elsif($cmd eq 'smtpd') {
	if(/\[\d+\]: \w+: client=/o) {
	    #
	    # Warning: this code in two places!
	    #
	    ++$rcvPerHr[$msgHr];
	    ++${$msgsPerDay{$revMsgDateStr}}[0];
	    ++$msgsRcvd;
	    ++$rcvdMsg{$qid};	# quick-set a flag
	} elsif(my($rejSubTyp) = /\[\d+\]: \w+: (reject(?:_warning)?|hold|discard): /o) {
	    if($rejSubTyp eq 'reject') {
		proc_smtpd_reject($_, \%rejects, \$msgsRjctd,
				  \$rejPerHr[$msgHr],
				  \${$msgsPerDay{$revMsgDateStr}}[4]);
	    } elsif($rejSubTyp eq 'reject_warning') {
		proc_smtpd_reject($_, \%warns, \$msgsWrnd,
				  \$rejPerHr[$msgHr],
				  \${$msgsPerDay{$revMsgDateStr}}[4]);
	    } elsif($rejSubTyp eq 'hold') {
		proc_smtpd_reject($_, \%holds, \$msgsHld,
				  \$rejPerHr[$msgHr],
				  \${$msgsPerDay{$revMsgDateStr}}[4]);
	    } elsif($rejSubTyp eq 'discard') {
		proc_smtpd_reject($_, \%discards, \$msgsDscrdd,
				  \$rejPerHr[$msgHr],
				  \${$msgsPerDay{$revMsgDateStr}}[4]);
	    }
	}
# ---Begin: SMTPD_STATS_SUPPORT---
	else {
	    next unless(defined($opts{'smtpdStats'}));
	    if(/: connect from /o) {
		/\/smtpd\[(\d+)\]: /o;
		@{$connTime{$1}} =
		    ($msgYr, $msgMon + 1, $msgDay, $msgHr, $msgMin, $msgSec);
	    } elsif(/: disconnect from /o) {
		my ($pid, $hostID) = /\/smtpd\[(\d+)\]: disconnect from (.+)$/o;
		if(exists($connTime{$pid})) {
		    $hostID = gimme_domain($hostID);
		    my($d, $h, $m, $s) = Delta_DHMS(@{$connTime{$pid}},
			$msgYr, $msgMon + 1, $msgDay, $msgHr, $msgMin, $msgSec);
		    delete($connTime{$pid});	# dispose of no-longer-needed item
		    my $tSecs = (86400 * $d) + (3600 * $h) + (60 * $m) + $s;

		    ++$smtpdPerHr[$msgHr][0];
		    $smtpdPerHr[$msgHr][1] += $tSecs;
		    $smtpdPerHr[$msgHr][2] = $tSecs if($tSecs > $smtpdPerHr[$msgHr][2]);

		    unless(${$smtpdPerDay{$revMsgDateStr}}[0]++) {
			${$smtpdPerDay{$revMsgDateStr}}[1] = 0;
			${$smtpdPerDay{$revMsgDateStr}}[2] = 0;
		    }
		    ${$smtpdPerDay{$revMsgDateStr}}[1] += $tSecs;
		    ${$smtpdPerDay{$revMsgDateStr}}[2] = $tSecs
			if($tSecs > ${$smtpdPerDay{$revMsgDateStr}}[2]);

		    unless(${$smtpdPerDom{$hostID}}[0]++) {
			${$smtpdPerDom{$hostID}}[1] = 0;
			${$smtpdPerDom{$hostID}}[2] = 0;
		    }
		    ${$smtpdPerDom{$hostID}}[1] += $tSecs;
		    ${$smtpdPerDom{$hostID}}[2] = $tSecs
			if($tSecs > ${$smtpdPerDom{$hostID}}[2]);

		    ++$smtpdConnCnt;
		    $smtpdTotTime += $tSecs;
		}
	    }
	}
# ---End: SMTPD_STATS_SUPPORT---
    } else {
	my $toRmdr;
	if((($addr, $size) = /from=<([^>]*)>, size=(\d+)/o) == 2)
	{
	    next if($msgSizes{$qid});	# avoid double-counting!
	    if($addr) {
		if($opts{'m'} && $addr =~ /^(.*!)*([^!]+)!([^!@]+)@([^\.]+)$/o) {
		    $addr = "$4!" . ($1? "$1" : "") . $3 . "\@$2";
		}
		$addr =~ s/(@.+)/\L$1/o unless($opts{'i'});
		$addr = lc($addr) if($opts{'i'});
		$addr = verp_mung($addr);
	    } else {
		$addr = "from=<>"
	    }
	    $msgSizes{$qid} = $size;
	    push(@{$msgDetail{$qid}}, $addr) if($opts{'e'});
	    # Avoid counting forwards
	    if($rcvdMsg{$qid}) {
		(my $domAddr = $addr) =~ s/^[^@]+\@//o;	# get domain only
		++$sendgDomCnt
		    unless(${$sendgDom{$domAddr}}[$msgCntI]);
		++${$sendgDom{$domAddr}}[$msgCntI];
		${$sendgDom{$domAddr}}[$msgSizeI] += $size;
	        ++$sendgUserCnt unless(${$sendgUser{$addr}}[$msgCntI]);
		++${$sendgUser{$addr}}[$msgCntI];
		${$sendgUser{$addr}}[$msgSizeI] += $size;
		$sizeRcvd += $size;
		delete($rcvdMsg{$qid});		# limit hash size
	    }
	}
	elsif((($addr, $relay, $delay, $status, $toRmdr) =
		/to=<([^>]*)>, (?:orig_to=<[^>]*>, )?relay=([^,]+), delay=([^,]+), status=([^ ]+)(.*)$/o) >= 4)
	{

	    if($opts{'m'} && $addr =~ /^(.*!)*([^!]+)!([^!@]+)@([^\.]+)$/o) {
		$addr = "$4!" . ($1? "$1" : "") . $3 . "\@$2";
	    }
	    $addr =~ s/(@.+)/\L$1/o unless($opts{'i'});
	    $addr = lc($addr) if($opts{'i'});
	    (my $domAddr = $addr) =~ s/^[^@]+\@//o;	# get domain only
	    if($status eq 'sent') {

		# was it actually forwarded, rather than delivered?
		if($toRmdr =~ /forwarded as /o) {
		    ++$msgsFwdd;
		    next;
		}
		++$recipDomCnt unless(${$recipDom{$domAddr}}[$msgCntI]);
		++${$recipDom{$domAddr}}[$msgCntI];
		${$recipDom{$domAddr}}[$msgDlyAvgI] += $delay;
		if(! ${$recipDom{$domAddr}}[$msgDlyMaxI] ||
		   $delay > ${$recipDom{$domAddr}}[$msgDlyMaxI])
		{
		    ${$recipDom{$domAddr}}[$msgDlyMaxI] = $delay
		}
		++$recipUserCnt unless(${$recipUser{$addr}}[$msgCntI]);
		++${$recipUser{$addr}}[$msgCntI];
		++$dlvPerHr[$msgHr];
		++${$msgsPerDay{$revMsgDateStr}}[1];
		++$msgsDlvrd;
		if($msgSizes{$qid}) {
		    ${$recipDom{$domAddr}}[$msgSizeI] += $msgSizes{$qid};
		    ${$recipUser{$addr}}[$msgSizeI] += $msgSizes{$qid};
		    $sizeDlvrd += $msgSizes{$qid};
		} else {
		    ${$recipDom{$domAddr}}[$msgSizeI] += 0;
		    ${$recipUser{$addr}}[$msgSizeI] += 0;
		    $noMsgSize{$qid} = $addr;
		    push(@{$msgDetail{$qid}}, "(sender not in log)") if($opts{'e'});
		    # put this back later? mebbe with -v?
		    # msg_warn("no message size for qid: $qid");
		}
		push(@{$msgDetail{$qid}}, $addr) if($opts{'e'});
	    } elsif($status eq 'deferred') {
		my ($deferredReas) = /, status=deferred \(([^\)]+)/o;
		unless(defined($opts{'verbMsgDetail'})) {
		    $deferredReas = said_string_trimmer($deferredReas, 65);
		    $deferredReas =~ s/^\d{3} //o;
		    $deferredReas =~ s/^connect to //o;
		}
		++$deferred{$cmd}{$deferredReas};
                ++$dfrPerHr[$msgHr];
		++${$msgsPerDay{$revMsgDateStr}}[2];
		++$msgsDfrdCnt;
		++$msgsDfrd unless($msgDfrdFlgs{$qid}++);
		++${$recipDom{$domAddr}}[$msgDfrsI];
		if(! ${$recipDom{$domAddr}}[$msgDlyMaxI] ||
		   $delay > ${$recipDom{$domAddr}}[$msgDlyMaxI])
		{
		    ${$recipDom{$domAddr}}[$msgDlyMaxI] = $delay
		}
	    } elsif($status eq 'bounced') {
		my ($bounceReas) = /, status=bounced \((.+)\)/o;
		unless(defined($opts{'verbMsgDetail'})) {
		    $bounceReas = said_string_trimmer($bounceReas, 66);
		    $bounceReas =~ s/^\d{3} //o;
		}
		++$bounced{$relay}{$bounceReas};
                ++$bncPerHr[$msgHr];
		++${$msgsPerDay{$revMsgDateStr}}[3];
		++$msgsBncd;
	    } else {
#		print UNPROCD "$_\n";
	    }
	}
	elsif($cmd eq 'pickup' && /: (sender|uid)=/o) {
	    #
	    # Warning: this code in two places!
	    #
	    ++$rcvPerHr[$msgHr];
	    ++${$msgsPerDay{$revMsgDateStr}}[0];
	    ++$msgsRcvd;
	    ++$rcvdMsg{$qid};	# quick-set a flag
	}
	elsif($cmd eq 'smtp') {
	    if(/.* connect to ([^:]+): ([^;]+); address [\d\.]+ port.*$/o) {
		++$smtpMsgs{lc($2)}{$1};
	    } elsif(/.* connect to ([^[]+)\[[\d\.]+]: (.+) \(port \d+\)$/o) {
		++$smtpMsgs{lc($2)}{$1};
	    } else {
#		print UNPROCD "$_\n";
	    }
	}
	else
	{
#	    print UNPROCD "$_\n";
	}
    }
}

# debugging
#close(UNPROCD) ||
#    die "problem closing \"unprocessed\": $!\n";

# Calculate percentage of messages rejected and discarded
my $msgsRjctdPct = 0;
my $msgsDscrddPct = 0;
if(my $msgsTotal = $msgsDlvrd + $msgsRjctd + $msgsDscrdd) {
    $msgsRjctdPct = int(($msgsRjctd/$msgsTotal) * 100);
    $msgsDscrddPct = int(($msgsDscrdd/$msgsTotal) * 100);
}

if(defined($dateStr)) {
    print "Postfix log summaries for $dateStr\n";
}

print "\nGrand Totals\n------------\n";
print "messages\n\n";
printf " %6d%s  received\n", adj_int_units($msgsRcvd);
printf " %6d%s  delivered\n", adj_int_units($msgsDlvrd);
printf " %6d%s  forwarded\n", adj_int_units($msgsFwdd);
printf " %6d%s  deferred", adj_int_units($msgsDfrd);
printf "  (%d%s deferrals)", adj_int_units($msgsDfrdCnt) if($msgsDfrdCnt);
print "\n";
printf " %6d%s  bounced\n", adj_int_units($msgsBncd);
printf " %6d%s  rejected (%d%%)\n", adj_int_units($msgsRjctd), $msgsRjctdPct;
printf " %6d%s  reject warnings\n", adj_int_units($msgsWrnd);
printf " %6d%s  held\n", adj_int_units($msgsHld);
printf " %6d%s  discarded (%d%%)\n", adj_int_units($msgsDscrdd), $msgsDscrddPct;
print "\n";
printf " %6d%s  bytes received\n", adj_int_units($sizeRcvd);
printf " %6d%s  bytes delivered\n", adj_int_units($sizeDlvrd);
printf " %6d%s  senders\n", adj_int_units($sendgUserCnt);
printf " %6d%s  sending hosts/domains\n", adj_int_units($sendgDomCnt);
printf " %6d%s  recipients\n", adj_int_units($recipUserCnt);
printf " %6d%s  recipient hosts/domains\n", adj_int_units($recipDomCnt);

# ---Begin: SMTPD_STATS_SUPPORT---
if(defined($opts{'smtpdStats'})) {
    print "\nsmtpd\n\n";
    printf "  %6d%s  connections\n", adj_int_units($smtpdConnCnt);
    printf "  %6d%s  hosts/domains\n", adj_int_units(int(keys %smtpdPerDom));
    printf "  %6d   avg. connect time (seconds)\n",
	$smtpdConnCnt > 0? ($smtpdTotTime / $smtpdConnCnt) + .5 : 0;
    {
	my ($sec, $min, $hr) = get_smh($smtpdTotTime);
	printf " %2d:%02d:%02d  total connect time\n",
	  $hr, $min, $sec;
    }
}
# ---End: SMTPD_STATS_SUPPORT---

print "\n";

print_problems_reports() if(defined($opts{'pf'}));

print_per_day_summary(\%msgsPerDay) if($dayCnt > 1);
print_per_hour_summary(\@rcvPerHr, \@dlvPerHr, \@dfrPerHr, \@bncPerHr,
    \@rejPerHr, $dayCnt);

print_recip_domain_summary(\%recipDom, $opts{'h'});
print_sending_domain_summary(\%sendgDom, $opts{'h'});

# ---Begin: SMTPD_STATS_SUPPORT---
if(defined($opts{'smtpdStats'})) {
    print_per_day_smtpd(\%smtpdPerDay, $dayCnt) if($dayCnt > 1);
    print_per_hour_smtpd(\@smtpdPerHr, $dayCnt);
    print_domain_smtpd_summary(\%smtpdPerDom, $opts{'h'});
}
# ---End: SMTPD_STATS_SUPPORT---

print_user_data(\%sendgUser, "Senders by message count", $msgCntI, $opts{'u'}, $opts{'q'});
print_user_data(\%recipUser, "Recipients by message count", $msgCntI, $opts{'u'}, $opts{'q'});
print_user_data(\%sendgUser, "Senders by message size", $msgSizeI, $opts{'u'}, $opts{'q'});
print_user_data(\%recipUser, "Recipients by message size", $msgSizeI, $opts{'u'}, $opts{'q'});

print_hash_by_key(\%noMsgSize, "Messages with no size data", 0, 1);

print_problems_reports() unless(defined($opts{'pf'}));

print_detailed_msg_data(\%msgDetail, "Message detail", $opts{'q'}) if($opts{'e'});

# Print "problems" reports
sub print_problems_reports {
    unless($opts{'noDeferralDetail'}) {
	print_nested_hash(\%deferred, "message deferral detail", $opts{'q'});
    }
    unless($opts{'noBounceDetail'}) {
	print_nested_hash(\%bounced, "message bounce detail (by relay)", $opts{'q'});
    }
    unless($opts{'noRejectDetail'}) {
	print_nested_hash(\%rejects, "message reject detail", $opts{'q'});
	print_nested_hash(\%warns, "message reject warning detail", $opts{'q'});
	print_nested_hash(\%holds, "message hold detail", $opts{'q'});
	print_nested_hash(\%discards, "message discard detail", $opts{'q'});
    }
    print_nested_hash(\%smtpMsgs, "smtp delivery failures", $opts{'q'});
    print_nested_hash(\%warnings, "Warnings", $opts{'q'});
    print_nested_hash(\%fatals, "Fatal Errors", 0, $opts{'q'});
    print_nested_hash(\%panics, "Panics", 0, $opts{'q'});
    print_hash_by_cnt_vals(\%masterMsgs,"Master daemon messages", 0, $opts{'q'});
}

if($opts{'mailq'}) {
    # flush stdout first cuz of asynchronousity
    $| = 1;
    print "\nCurrent Mail Queue\n------------------\n";
    system($mailqCmd);
}

# print "per-day" traffic summary
# (done in a subroutine only to keep main-line code clean)
sub print_per_day_summary {
    my($msgsPerDay) = @_;
    my $value;
    print <<End_Of_Per_Day_Heading;

Per-Day Traffic Summary
    date          received  delivered   deferred    bounced     rejected
    --------------------------------------------------------------------
End_Of_Per_Day_Heading

    foreach (sort { $a <=> $b } keys(%$msgsPerDay)) {
	my ($msgYr, $msgMon, $msgDay) = unpack("A4 A2 A2", $_);
	if($isoDateTime) {
	    printf "    %04d-%02d-%02d ", $msgYr, $msgMon + 1, $msgDay
	} else {
	    my $msgMonStr = $monthNames[$msgMon];
	    printf "    $msgMonStr %2d $msgYr", $msgDay;
	}
	foreach $value (@{$msgsPerDay->{$_}}) {
	    my $value2 = $value? $value : 0;
	    printf "    %6d%s", adj_int_units($value2);
	}
	print "\n";
    }
}

# print "per-hour" traffic summary
# (done in a subroutine only to keep main-line code clean)
sub print_per_hour_summary {
    my ($rcvPerHr, $dlvPerHr, $dfrPerHr, $bncPerHr, $rejPerHr, $dayCnt) = @_;
    my $reportType = $dayCnt > 1? 'Daily Average' : 'Summary';
    my ($hour, $value);
    print <<End_Of_Per_Hour_Heading;

Per-Hour Traffic $reportType
    time          received  delivered   deferred    bounced     rejected
    --------------------------------------------------------------------
End_Of_Per_Hour_Heading

    for($hour = 0; $hour < 24; ++$hour) {
	if($isoDateTime) {
	    printf "    %02d:00-%02d:00", $hour, $hour + 1;
	} else {
	    printf "    %02d00-%02d00  ", $hour, $hour + 1;
	}
	foreach $value (@$rcvPerHr[$hour], @$dlvPerHr[$hour],
			   @$dfrPerHr[$hour], @$bncPerHr[$hour],
			   @$rejPerHr[$hour])
	{
	    my $units = ' ';
	    $value = ($value / $dayCnt) + 0.5 if($dayCnt);
	    printf "    %6d%s", adj_int_units($value);
	}
	print "\n";
    }
}

# print "per-recipient-domain" traffic summary
# (done in a subroutine only to keep main-line code clean)
sub print_recip_domain_summary {
    use vars '$hashRef';
    local($hashRef) = $_[0];
    my($cnt) = $_[1];
    return if($cnt == 0);
    my $topCnt = $cnt > 0? "(top $cnt)" : "";
    my $avgDly;
    print <<End_Of_Recip_Domain_Heading;

Host/Domain Summary: Message Delivery $topCnt
 sent cnt  bytes   defers   avg dly max dly host/domain
 -------- -------  -------  ------- ------- -----------
End_Of_Recip_Domain_Heading

    foreach (reverse sort by_count_then_size keys(%$hashRef)) {
	# there are only delay values if anything was sent
	if(${$hashRef->{$_}}[$msgCntI]) {
	    $avgDly = (${$hashRef->{$_}}[$msgDlyAvgI] /
		       ${$hashRef->{$_}}[$msgCntI]);
	} else {
	    $avgDly = 0;
	}
	printf " %6d%s  %6d%s  %6d%s  %5.1f %s  %5.1f %s  %s\n",
	    adj_int_units(${$hashRef->{$_}}[$msgCntI]),
	    adj_int_units(${$hashRef->{$_}}[$msgSizeI]),
	    adj_int_units(${$hashRef->{$_}}[$msgDfrsI]),
	    adj_time_units($avgDly),
	    adj_time_units(${$hashRef->{$_}}[$msgDlyMaxI]),
	    $_;
	last if --$cnt == 0;
    }
}

# print "per-sender-domain" traffic summary
# (done in a subroutine only to keep main-line code clean)
sub print_sending_domain_summary {
    use vars '$hashRef';
    local($hashRef) = $_[0];
    my($cnt) = $_[1];
    return if($cnt == 0);
    my $topCnt = $cnt > 0? "(top $cnt)" : "";
    print <<End_Of_Sender_Domain_Heading;

Host/Domain Summary: Messages Received $topCnt
 msg cnt   bytes   host/domain
 -------- -------  -----------
End_Of_Sender_Domain_Heading

    foreach (reverse sort by_count_then_size keys(%$hashRef)) {
	printf " %6d%s  %6d%s  %s\n",
	    adj_int_units(${$hashRef->{$_}}[$msgCntI]),
	    adj_int_units(${$hashRef->{$_}}[$msgSizeI]),
	    $_;
	last if --$cnt == 0;
    }
}

# print "per-user" data sorted in descending order
# order (i.e.: highest first)
sub print_user_data {
    my($hashRef, $title, $index, $cnt, $quiet) = @_;
    my $dottedLine;
    return if($cnt == 0);
    $title = sprintf "%s%s", $cnt > 0? "top $cnt " : "", $title;
    unless(%$hashRef) {
	return if($quiet);
	$dottedLine = ": none";
    } else {
	$dottedLine = "\n" . "-" x length($title);
    }
    printf "\n$title$dottedLine\n";
    foreach (reverse sort { ${$hashRef->{$a}}[$index] <=>
	                    ${$hashRef->{$b}}[$index] }
	keys(%$hashRef))
    {
	printf " %6d%s  %s\n", adj_int_units(${$hashRef->{$_}}[$index]), $_;
	last if --$cnt == 0;
    }
}

# ---Begin: SMTPD_STATS_SUPPORT---

# print "per-hour" smtpd connection summary
# (done in a subroutine only to keep main-line code clean)
sub print_per_hour_smtpd {
    my ($smtpdPerHr, $dayCnt) = @_;
    my ($hour, $value);
    if($dayCnt > 1) {
	print <<End_Of_Per_Hour_Smtp_Average;

Per-Hour SMTPD Connection Daily Average
    hour        connections    time conn.
    -------------------------------------
End_Of_Per_Hour_Smtp_Average
    } else {
	print <<End_Of_Per_Hour_Smtp;

Per-Hour SMTPD Connection Summary
    hour        connections    time conn.    avg./conn.   max. time
    --------------------------------------------------------------------
End_Of_Per_Hour_Smtp
    }

    for($hour = 0; $hour < 24; ++$hour) {
	$smtpdPerHr[$hour]->[0] || next;
	my $avg = int($smtpdPerHr[$hour]->[0]?
	    ($smtpdPerHr[$hour]->[1]/$smtpdPerHr[$hour]->[0]) + .5 : 0);
	if($dayCnt > 1) {
	    $smtpdPerHr[$hour]->[0] /= $dayCnt;
	    $smtpdPerHr[$hour]->[1] /= $dayCnt;
	    $smtpdPerHr[$hour]->[0] += .5;
	    $smtpdPerHr[$hour]->[1] += .5;
	}
	my($sec, $min, $hr) = get_smh($smtpdPerHr[$hour]->[1]);

	if($isoDateTime) {
	    printf "    %02d:00-%02d:00", $hour, $hour + 1;
	} else {
	    printf "    %02d00-%02d00  ", $hour, $hour + 1;
	}
	printf "   %6d%s       %2d:%02d:%02d",
	    adj_int_units($smtpdPerHr[$hour]->[0]),
	    $hr, $min, $sec;
	if($dayCnt < 2) {
	    printf "      %6ds      %6ds",
		$avg,
		$smtpdPerHr[$hour]->[2];
	}
	print "\n";
    }
}


# print "per-day" smtpd connection summary
# (done in a subroutine only to keep main-line code clean)
sub print_per_day_smtpd {
    my ($smtpdPerDay, $dayCnt) = @_;
    print <<End_Of_Per_Day_Smtp;

Per-Day SMTPD Connection Summary
    date        connections    time conn.    avg./conn.   max. time
    --------------------------------------------------------------------
End_Of_Per_Day_Smtp

    foreach (sort { $a <=> $b } keys(%$smtpdPerDay)) {
	my ($msgYr, $msgMon, $msgDay) = unpack("A4 A2 A2", $_);
	if($isoDateTime) {
	    printf "    %04d-%02d-%02d ", $msgYr, $msgMon + 1, $msgDay
	} else {
	    my $msgMonStr = $monthNames[$msgMon];
	    printf "    $msgMonStr %2d $msgYr", $msgDay;
	}

	my $avg = (${$smtpdPerDay{$_}}[1]/${$smtpdPerDay{$_}}[0]) + .5;
	my($sec, $min, $hr) = get_smh(${$smtpdPerDay{$_}}[1]);

	printf "   %6d%s       %2d:%02d:%02d      %6ds      %6ds\n",
	    adj_int_units(${$smtpdPerDay{$_}}[0]),
	    $hr, $min, $sec,
	    $avg,
	    ${$smtpdPerDay{$_}}[2];
    }
}

# print "per-domain-smtpd" connection summary
# (done in a subroutine only to keep main-line code clean)
sub print_domain_smtpd_summary {
    use vars '$hashRef';
    local($hashRef) = $_[0];
    my($cnt) = $_[1];
    return if($cnt == 0);
    my $topCnt = $cnt > 0? "(top $cnt)" : "";
    my $avgDly;
    print <<End_Of_Domain_Smtp_Heading;

Host/Domain Summary: SMTPD Connections $topCnt
 connections  time conn.  avg./conn.  max. time  host/domain
 -----------  ----------  ----------  ---------  -----------
End_Of_Domain_Smtp_Heading

    foreach (reverse sort by_count_then_size keys(%$hashRef)) {
	my $avg = (${$hashRef->{$_}}[1]/${$hashRef->{$_}}[0]) + .5;
	my ($sec, $min, $hr) = get_smh(${$hashRef->{$_}}[1]);

	printf "  %6d%s      %2d:%02d:%02d     %6ds    %6ds   %s\n",
	    adj_int_units(${$hashRef->{$_}}[0]),
	    $hr, $min, $sec,
	    $avg,
	    ${$hashRef->{$_}}[2],
	    $_;
	last if --$cnt == 0;
    }
}

# ---End: SMTPD_STATS_SUPPORT---

# print hash contents sorted by numeric values in descending
# order (i.e.: highest first)
sub print_hash_by_cnt_vals {
    my($hashRef, $title, $cnt, $quiet) = @_;
    my $dottedLine;
    $title = sprintf "%s%s", $cnt? "top $cnt " : "", $title;
    unless(%$hashRef) {
	return if($quiet);
	$dottedLine = ": none";
    } else {
	$dottedLine = "\n" . "-" x length($title);
    }
    printf "\n$title$dottedLine\n";
    really_print_hash_by_cnt_vals($hashRef, $cnt, ' ');
}

# print hash contents sorted by key in ascending order
sub print_hash_by_key {
    my($hashRef, $title, $cnt, $quiet) = @_;
    my $dottedLine;
    $title = sprintf "%s%s", $cnt? "first $cnt " : "", $title;
    unless(%$hashRef) {
	return if($quiet);
	$dottedLine = ": none";
    } else {
	$dottedLine = "\n" . "-" x length($title);
    }
    printf "\n$title$dottedLine\n";
    foreach (sort keys(%$hashRef))
    {
	printf " %s  %s\n", $_, $hashRef->{$_};
	last if --$cnt == 0;
    }
}

# print "nested" hashes
sub print_nested_hash {
    my($hashRef, $title, $quiet) = @_;
    my $dottedLine;
    unless(%$hashRef) {
	return if($quiet);
	$dottedLine = ": none";
    } else {
	$dottedLine = "\n" . "-" x length($title);
    }
    printf "\n$title$dottedLine\n";
    walk_nested_hash($hashRef, 0);
}

# "walk" a "nested" hash
sub walk_nested_hash {
    my ($hashRef, $level) = @_;
    $level += 2;
    my $indents = ' ' x $level;
    my ($keyName, $hashVal) = each(%$hashRef);

    if(ref($hashVal) eq 'HASH') {
	foreach (sort keys %$hashRef) {
	    print "$indents$_\n";
	    walk_nested_hash($hashRef->{$_}, $level);
	}
    } else {
	really_print_hash_by_cnt_vals($hashRef, 0, $indents);
#	print "\n"
    }
}

# print per-message info in excruciating detail :-)
sub print_detailed_msg_data {
    use vars '$hashRef';
    local($hashRef) = $_[0];
    my($title, $quiet) = @_[1,2];
    my $dottedLine;
    unless(%$hashRef) {
	return if($quiet);
	$dottedLine = ": none";
    } else {
	$dottedLine = "\n" . "-" x length($title);
    }
    printf "\n$title$dottedLine\n";
    foreach (sort by_domain_then_user keys(%$hashRef))
    {
	printf " %s  %s\n", $_, shift(@{$hashRef->{$_}});
	foreach (@{$hashRef->{$_}}) {
	    print "   $_\n";
	}
	print "\n";
    }
}

# *really* print hash contents sorted by numeric values in descending
# order (i.e.: highest first) :-)
sub really_print_hash_by_cnt_vals {
    my($hashRef, $cnt, $indents) = @_;

    foreach (reverse sort { $hashRef->{$a} <=> $hashRef->{$b} }
	keys(%$hashRef))
    {
	printf "$indents%6d%s  %s\n", adj_int_units($hashRef->{$_}), $_;
	last if --$cnt == 0;
    }
}

# subroutine to sort by domain, then user in domain, then by queue i.d.
# Note: mixing Internet-style domain names and UUCP-style bang-paths
# may confuse this thing.  An attempt is made to use the first host
# preceding the username in the bang-path as the "domain" if none is
# found otherwise.
sub by_domain_then_user {
    # first see if we can get "user@somedomain"
    my($userNameA, $domainA) = split(/\@/, ${$hashRef->{$a}}[0]);
    my($userNameB, $domainB) = split(/\@/, ${$hashRef->{$b}}[0]);

    # try "somedomain!user"?
    ($userNameA, $domainA) = (split(/!/, ${$hashRef->{$a}}[0]))[-1,-2]
	unless($domainA);
    ($userNameB, $domainB) = (split(/!/, ${$hashRef->{$b}}[0]))[-1,-2]
	unless($domainB);

    # now re-order "mach.host.dom"/"mach.host.do.co" to
    # "host.dom.mach"/"host.do.co.mach"
    $domainA =~ s/^(.*)\.([^\.]+)\.([^\.]{3}|[^\.]{2,3}\.[^\.]{2})$/$2.$3.$1/o
	if($domainA);
    $domainB =~ s/^(.*)\.([^\.]+)\.([^\.]{3}|[^\.]{2,3}\.[^\.]{2})$/$2.$3.$1/o
	if($domainB);

    # oddly enough, doing this here is marginally faster than doing
    # an "if-else", above.  go figure.
    $domainA = "" unless($domainA);
    $domainB = "" unless($domainB);

    if($domainA lt $domainB) {
	return -1;
    } elsif($domainA gt $domainB) {
	return 1;
    } else {
	# disregard leading bang-path
	$userNameA =~ s/^.*!//o;
	$userNameB =~ s/^.*!//o;
	if($userNameA lt $userNameB) {
	    return -1;
	} elsif($userNameA gt $userNameB) {
	    return 1;
	} else {
	    if($a lt $b) {
		return -1;
	    } elsif($a gt $b) {
		return 1;
	    }
	}
    }
    return 0;
}

# Subroutine used by host/domain reports to sort by count, then size.
# We "fix" un-initialized values here as well.  Very ugly and un-
# structured to do this here - but it's either that or the callers
# must run through the hashes twice :-(.
sub by_count_then_size {
    ${$hashRef->{$a}}[$msgCntI] = 0 unless(${$hashRef->{$a}}[$msgCntI]);
    ${$hashRef->{$b}}[$msgCntI] = 0 unless(${$hashRef->{$b}}[$msgCntI]);
    if(${$hashRef->{$a}}[$msgCntI] == ${$hashRef->{$b}}[$msgCntI]) {
	${$hashRef->{$a}}[$msgSizeI] = 0 unless(${$hashRef->{$a}}[$msgSizeI]);
	${$hashRef->{$b}}[$msgSizeI] = 0 unless(${$hashRef->{$b}}[$msgSizeI]);
	return(${$hashRef->{$a}}[$msgSizeI] <=>
	       ${$hashRef->{$b}}[$msgSizeI]);
    } else {
	return(${$hashRef->{$a}}[$msgCntI] <=>
	       ${$hashRef->{$b}}[$msgCntI]);
    }
}

# return a date string to match in log
sub get_datestr {
    my $dateOpt = $_[0];

    my $aDay = 60 * 60 * 24;

    my $time = time();
    if($dateOpt eq "yesterday") {
	$time -= $aDay;
    } elsif($dateOpt ne "today") {
	die "$usageMsg\n";
    }
    my ($t_mday, $t_mon) = (localtime($time))[3,4];

    return sprintf("%s %2d", $monthNames[$t_mon], $t_mday);
}

# if there's a real domain: uses that.  Otherwise uses the first
# three octets of the IP addr.  (In the latter case: usually pretty
# safe to assume it's a dialup with a class C IP addr.)  Lower-
# cases returned domain name.
sub gimme_domain {
    $_ = $_[0];
    my($domain, $ipAddr);
 
    # split domain/ipaddr into separates
    unless((($domain, $ipAddr) = /^([^\[]+)\[([^\]]+)\]:?\s*$/o) == 2) {
	# more exhaustive method
        ($domain, $ipAddr) = /^([^\[\(]+)[\[\(]([^\]\)]+)[\]\)]:?\s*$/o;
    }
 
#    print STDERR "dbg: in=\"$_\", domain=\"$domain\", ipAddr=\"$ipAddr\"\n";
    # now re-order "mach.host.dom"/"mach.host.do.co" to
    # "host.dom.mach"/"host.do.co.mach"
    if($domain eq 'unknown') {
        $domain = $ipAddr;
	# For identifying the host part on a Class C network (commonly
	# seen with dial-ups) the following is handy.
        # $domain =~ s/\.\d+$//o;
    } else {
        $domain =~
            s/^(.*)\.([^\.]+)\.([^\.]{3}|[^\.]{2,3}\.[^\.]{2})$/\L$2.$3/o;
    }
 
    return $domain;
}

# Return (value, units) for integer
sub adj_int_units {
    my $value = $_[0];
    my $units = ' ';
    
    $value = 0 unless($value);
    
    #if($value > $divByOneMegAt) {
    #    $value /= $oneMeg;
    #    $units = 'm'
    #} elsif($value > $divByOneKAt) {
    #    $value /= $oneK;
    #    $units = 'k'
    #}
    
    return($value, $units);
}

# Return (value, units) for time
sub adj_time_units {
    my $value = $_[0];
    my $units = 's';
    $value = 0 unless($value);
    if($value > 3600) {
	$value /= 3600;
	$units = 'h'
    } elsif($value > 60) {
	$value /= 60;
	$units = 'm'
    }
    return($value, $units);
}

# Trim a "said:" string, if necessary.  Add elipses to show it.
sub said_string_trimmer {
    my($trimmedString, $maxLen) = @_;

    while(length($trimmedString) > $maxLen) {
	if($trimmedString =~ /^.* said: /o) {
	    $trimmedString =~ s/^.* said: //o;
	} elsif($trimmedString =~ /^.*: */o) {
	    $trimmedString =~ s/^.*?: *//o;
	} else {
	    $trimmedString = substr($trimmedString, 0, $maxLen - 3) . "...";
	    last;
	}
    }

    return $trimmedString;
}

# Trim a string, if necessary.  Add elipses to show it.
sub string_trimmer {
    my($trimmedString, $maxLen, $doNotTrim) = @_;

    $trimmedString = substr($trimmedString, 0, $maxLen - 3) . "..." 
	if(! $doNotTrim && (length($trimmedString) > $maxLen));
    return $trimmedString;
}

# Get seconds, minutes and hours from seconds
sub get_smh {
    my $sec = shift @_;
    my $hr = int($sec / 3600);
    $sec -= $hr * 3600;
    my $min = int($sec / 60);
    $sec -= $min * 60;
    return($sec, $min, $hr);
}

# Process smtpd rejects
sub proc_smtpd_reject {
    my ($logLine, $rejects, $msgsRjctd, $rejPerHr, $msgsPerDay) = @_;
    my ($rejTyp, $rejFrom, $rejRmdr, $rejReas);
    my ($from, $to);
    my $rejAddFrom = 0;

    # This could get real ugly!

    # First: get everything following the "reject: ", etc. token
    ($rejTyp, $rejFrom, $rejRmdr) = 
	($logLine =~ /^.* \b(?:reject(?:_warning)?|hold|discard): ([^ ]+) from ([^:]+): (.*)$/o);

    # Next: get the reject "reason"
    $rejReas = $rejRmdr;
    unless(defined($opts{'verbMsgDetail'})) {
	if($rejTyp eq "RCPT") {	# special treatment :-(
	    $rejReas =~ s/^(?:.*?[:;] )(?:\[[^\]]+\] )?([^;,]+)[;,].*$/$1/o;
	    $rejReas =~ s/^(Sender address rejected: [^:]+):.*$/$1/o;
	    $rejReas =~ s/(Client host|Sender address) .+? blocked/blocked/o;
	} elsif($rejTyp eq "MAIL") {	# *more* special treatment :-( grrrr...
	    $rejReas =~ s/^\d{3} (?:<.+>: )?([^;:]+)[;:]?.*$/$1/o;
	} else {
	    $rejReas =~ s/^(?:.*[:;] )?([^,]+).*$/$1/o;
	}
    }

    # Snag out the "from" and "to" addrs...
    # The "?" in the "to" expression is for when some spammer passes
    # us a long "HTML-style" "?"-separated address. We truncate at
    # the first "?" in the "to=<" field.
    unless((($from, $to) = $rejRmdr =~ m/from=<(.*?)>\s+to=<(.*?)[>\?]/o) == 2) {
	($to, $from) = $rejRmdr =~ m/\d{3} <(.*?)>: User unknown .+from=<(.*?)> proto=/o;
    }

    if(defined($from)) {
	$rejAddFrom = $opts{'rejAddFrom'};
	$from = verp_mung($from);
    }

    # stash in "triple-subscripted-array"
    if($rejReas =~ m/^Sender address rejected:/o) {
	# Sender address rejected: Domain not found
	# Sender address rejected: need fully-qualified address
	++$rejects->{$rejTyp}{$rejReas}{$from};
    } elsif($rejReas =~ m/^Recipient address rejected:/o) {
	# Recipient address rejected: Domain not found
	# Recipient address rejected: need fully-qualified address
	++$rejects->{$rejTyp}{$rejReas}{$to};
    } elsif($rejReas =~ s/^.*?\d{3} (Improper use of SMTP command pipelining);.*$/$1/o) {
	my ($src) = $logLine =~ /^.+? from ([^:]+):.*$/o;
	++$rejects->{$rejTyp}{$rejReas}{$src};
    } elsif($rejReas =~ s/^.*?\d{3} (Message size exceeds fixed limit);.*$/$1/o) {
	my $rejData = gimme_domain($rejFrom);
	$rejData .= "  ($from)" if($rejAddFrom);
	++$rejects->{$rejTyp}{$rejReas}{$rejData};
    } else {
#	    print STDERR "dbg: unknown reject reason $rejReas !\n\n";
	my $rejData = gimme_domain($rejFrom);
	$rejData .= "  ($from)" if($rejAddFrom);
	++$rejects->{$rejTyp}{$rejReas}{$rejData};
    }
    ++$$msgsRjctd;
    ++$$rejPerHr;
    ++$$msgsPerDay;
}

# Hack for VERP (?) - convert address from somthing like
# "list-return-36-someuser=someplace.com@lists.domain.com"
# to "list-return-ID-someuser=someplace.com@lists.domain.com"
# to prevent per-user listing "pollution."  More aggressive
# munging converts to something like
# "list-return@lists.domain.com"  (Instead of "return," there
# may be numeric list name/id, "warn", "error", etc.?)
sub verp_mung {
    my $addr = $_[0];

    if(defined($opts{'verpMung'})) {
	$addr =~ s/((?:bounce[ds]?|no(?:list|reply|response)|return|sentto|\d+).*?)(?:[\+_\.\*-]\d+\b)+/$1-ID/oi;
	if($opts{'verpMung'} > 1) {
	    $addr =~ s/[\*-](\d+[\*-])?[^=\*-]+[=\*][^\@]+\@/\@/o;
	}
    }

    return $addr;
}

###
### Warning and Error Routines
###

# Emit warning message to stderr
sub msg_warn {
    warn "warning: $progName: $_[0]\n";
}


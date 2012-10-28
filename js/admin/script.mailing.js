// AdminPanel Mailing JavaScript Document

// Mailing (need jQuery)

var mailingFlag = true;

function mailingRun(
	accessKey, buttonObj, mailingResource,
	mailingSubject, mailingTitle, mailingMessage,
	accountsNum, accountsNumInStream,
	messageStart, messageSuccess, messageError
) {
	// Prepare
	jQuery(buttonObj).attr('disabled', 'disabled').addClass('ui-state-active');
	clearALL();
	findTIME();
	jQuery('#processbar').html(messageStart);
	// Mailing
	ctr_instance = 1;
	prc_instance = 0;
	accountsFrom = 0;
	mailingInstances = Math.ceil(accountsNum/accountsNumInStream);
	// Instance circle
	while (mailingFlag == true && ctr_instance <= mailingInstances) {
		dataStr = 'accounts_num_in_stream=' + accountsNumInStream + '&accounts_from=' + accountsFrom + '&mailing_resource=' + mailingResource + '&access_key=' + accessKey + '&mailing_subject=' + encodeURIComponent(mailingSubject) + '&mailing_title=' + encodeURIComponent(mailingTitle) + '&mailing_message=' + encodeURIComponent(mailingMessage) + '&accounts_num=' + accountsNum;
		jQuery.ajax({
			type: "POST",
			url: "../../application/admin/ajax/process.mailing.php",
			data: dataStr,
			success: function(html) {
				percent = Math.ceil(++prc_instance/mailingInstances*100);
				jQuery("#processbar").html('<span style="color: #ec2727;">' + percent + '%</span>');
				if (prc_instance == mailingInstances) {
					$.post('../../application/admin/ajax/process.mailing.log.php', dataStr );
					findTIME();
					jQuery(buttonObj).attr('disabled', '').removeClass('ui-state-active');
					jQuery('#processbar').html('<span style="color: #3aab3a;">' + messageSuccess + '</span>');
					if ($('#log_refresh_dialog').length > 0) {
						$('#log_refresh_dialog').dialog('open');
					}
				};
			},
			error: function(html) {
				++prc_instance;
				jQuery("#processbar").html('<span style="color: #ec2727;">' + messageError + '</span>');
				if (prc_instance == mailingInstances) {
					findTIME();
					jQuery(buttonObj).attr('disabled', '').removeClass('ui-state-active');
					jQuery('#processbar').html('<span style="color: #ec2727;">' + messageError + '</span>');
				};
			}
		});
		accountsFrom = accountsFrom + accountsNumInStream;
		ctr_instance++;
	};
	return false;
}

// Secondsmeter

var base = 60;
var clocktimer,dateObj,dh,dm,ds,ms;
var readout='';
var h=1;
var m=1;
var tm=1;
var s=0;
var ts=0;
var ms=0;
var show=true;
var init=0;

function clearALL() {
	clearTimeout(clocktimer);
	h=1;m=1;tm=1;s=0;ts=0;ms=0;
	init=0;show=true;
	readout='00:00:00.00';
	document.getElementById('secondsmeter').innerHTML=readout;
}

function startTIME() { 
	var cdateObj = new Date();
	var t = (cdateObj.getTime() - dateObj.getTime())-(s*1000);
	if (t>999) {
		s++;
	}
	if (s>=(m*base)) {
		ts=0;
		m++;
	} else {
		ts=parseInt((ms/100)+s);
		if(ts>=base) {
			ts=ts-((m-1)*base);
		}
	}
	if (m>(h*base)) {
		tm=1;
		h++;
	} else {
		tm=parseInt((ms/100)+m);
		if (tm>=base) {
			tm=tm-((h-1)*base);
		}
	}
	ms = Math.round(t/10);
	if (ms>99) {
		ms=0;
	}
	if (ms==0) {
		ms='00';
	}
	if (ms>0&&ms<=9) {
		ms = '0'+ms;
	}
	if (ts>0) {
		ds = ts;
		if (ts<10) {
			ds = '0'+ts;
		}
	} else {
		ds = '00';
	}
	dm=tm-1;
	if (dm>0) {
		if (dm<10) {
			dm = '0'+dm;
		}
	} else {
		dm = '00';
	}
	dh=h-1;
	if (dh>0) {
		if (dh<10) {
			dh = '0'+dh;
		}
	} else {
		dh = '00';
	}
	readout = dh + ':' + dm + ':' + ds + '.' + ms;
	if (show==true) {
		document.getElementById('secondsmeter').innerHTML = readout;
	}
	clocktimer = setTimeout("startTIME()",60);
}

function findTIME() {
	if (init==0) {
		dateObj = new Date();
		startTIME();
		init=1;
	} else {
		if(show==true) {
			show=false;
		} else {
			show=true; 
		}
	}
}
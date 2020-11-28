var membersList = [];

class Member {
	constructor(name){
		this.name = name;
	}

	getName(){
		return this.name;
	}
}

window.onload = () => {
	loadMembers();
	setInputListener();
	searchMember("");
}

function setInputListener(){
	getSearchInput().addEventListener("input", (e) => {
		searchMember(e.target.value);
	});
}

function searchMember(name){
	let html = "";
	membersList.forEach((element) => {
		let memberName = element.getName();
		if(memberName.toLowerCase().trim().includes(name.toLowerCase().trim())){
			html += `<li>${memberName}</li>`;
		}
	});

	getMembersList().innerHTML = html;
}

function getSearchInput(){
	return document.getElementById("searchInput");
}

function getMembersList(){
	return document.getElementById("membersList");
}

function loadMembers(){
	membersList.push(new Member("ABEL ESIEVO"));
	membersList.push(new Member("ABIAMUWA CHRIS"));
	membersList.push(new Member("ADAH LUCKY ENAKIRERHI"));
	membersList.push(new Member("ADELEKE SUNNY"));
	membersList.push(new Member("ADONIS E. IRHUE"));
	membersList.push(new Member("AIGBE ANDREW"));
	membersList.push(new Member("AKINJOBI JOY"));
	membersList.push(new Member("AKORI E. A."));
	membersList.push(new Member("AKPEWE UTOHWARE"));
	membersList.push(new Member("AKPOTOR ABRAHAM"));
	membersList.push(new Member("ALEX ARINZE"));
	membersList.push(new Member("AMEH E. O."));
	membersList.push(new Member("AMIBOR KINGSLEY CHIEDU"));
	membersList.push(new Member("ANIAMAKA ARINZE C."));
	membersList.push(new Member("ANUMA EDUVIE"));
	membersList.push(new Member("ANYAFU A. M."));
	membersList.push(new Member("AUGUSTINA OGBE (KURUE)"));
	membersList.push(new Member("BALEBO AKPOEBI"));
	membersList.push(new Member("BALOGUN LOVETH"));
	membersList.push(new Member("BEKIBELE P. E."));
	membersList.push(new Member("BLESSING UGBINI"));
	membersList.push(new Member("BRAIMAH EMMA"));
	membersList.push(new Member("BRIDEBA FUNBO GAIUS"));
	membersList.push(new Member("C.G.AMUDO"));
	membersList.push(new Member("CHARLES OCHEI"));
	membersList.push(new Member("CHIADIKA JOSHUA"));
	membersList.push(new Member("CHIBUZOR ADUOKU"));
	membersList.push(new Member("CHIEMEKE, MAGDAENE"));
	membersList.push(new Member("CHRIS UWAKINA Esq."));
	membersList.push(new Member("CHRISTOPHER E."));
	membersList.push(new Member("CHUKS T. AGUONYE"));
	membersList.push(new Member("CHUKWUELOKE JUDE CHIKEZIE"));
	membersList.push(new Member("CHUKWUEMEKA CHINEMEZE"));
	membersList.push(new Member("CHUKWURA VICTORIA"));
	membersList.push(new Member("CLETUS N. OBIEGBA"));
	membersList.push(new Member("DAFE OGHORO"));
	membersList.push(new Member("DAVID ORITSEJAFOR"));
	membersList.push(new Member("DR. JEFF CHISUMUM"));
	membersList.push(new Member("DR. EMESHILI M. E."));
	membersList.push(new Member("EBO K. DANIEL"));
	membersList.push(new Member("EGWAHOR JONATHAN"));
	membersList.push(new Member("EKAKITI DANIEL"));
	membersList.push(new Member("EKAKITI ROSE"));
	membersList.push(new Member("EKENE OHWOVORIOLE"));
	membersList.push(new Member("EKPEKUREDE SHEDREK"));
	membersList.push(new Member("EKWALE, A. R. O"));
	membersList.push(new Member("EMEFIENE A. WINIFRED"));
	membersList.push(new Member("EMEKA OTAKPOR"));
	membersList.push(new Member("EMIRI ISIOMA N."));
	membersList.push(new Member("EMMANUEL DOLOR"));
	membersList.push(new Member("ENEMUWA MIKE IBOBO"));
	membersList.push(new Member("ENGR. ITIVEH EREZI EMMANUEL"));
	membersList.push(new Member("ENGR. OGUM O. STANLEY"));
	membersList.push(new Member("ENUMA I. C."));
	membersList.push(new Member("ENWA JOY"));
	membersList.push(new Member("ERIAGANOMA EGO"));
	membersList.push(new Member("EVUMENA OMOEFE DANIEL"));
	membersList.push(new Member("EYAGOMARE MAY"));
	membersList.push(new Member("FELIX UWUMERI"));
	membersList.push(new Member("FRANK O. APOKWU"));
	membersList.push(new Member("FRANK O. AFOKWU DSC. KSC."));
	membersList.push(new Member("G. E. OKPUBULUKU"));
	membersList.push(new Member("GBEMUDU FRANKLYN"));
	membersList.push(new Member("GODWIN E. O."));
	membersList.push(new Member("HELEN ABOLOJE"));
	membersList.push(new Member("I. E. ILOBA - EJETEH"));
	membersList.push(new Member("IGBIKOGO ADESUWA"));
	membersList.push(new Member("IGBOKOYI ADEGUIS"));
	membersList.push(new Member("IKANONE PHILO"));
	membersList.push(new Member("IKE - WILLIAMS OLUSOLA"));
	membersList.push(new Member("IKEATOGBEI NICE"));
	membersList.push(new Member("ISAAC O. OKORO"));
	membersList.push(new Member("ISICHEI PATRICK"));
	membersList.push(new Member("ISICHEI STEPHEN"));
	membersList.push(new Member("IWELU KINGSLEY EMMANUEL"));
	membersList.push(new Member("IYABOR EDMOND"));
	membersList.push(new Member("IZUEGBU C."));
	membersList.push(new Member("JEFF URI-UNUM"));
	membersList.push(new Member("JEREMIAH I. ERUNEBE"));
	membersList.push(new Member("JOHN - ARUTE, F. E."));
	membersList.push(new Member("JOYCE TOKI"));
	membersList.push(new Member("JULIA UTULU"));
	membersList.push(new Member("KONWEA PHILOMINA ISIOMA"));
	membersList.push(new Member("LADY E. EJIRO UMUKORO"));
	membersList.push(new Member("LUCKY ANTHONY OFODU"));
	membersList.push(new Member("LUCKY OFODU NKEM P."));
	membersList.push(new Member("M. O. BRAGINA"));
	membersList.push(new Member("MARY EHRHAGANOMA"));
	membersList.push(new Member("MAXIMILLION C. IGBOAYAKA"));
	membersList.push(new Member("McCARTHY OBROTU"));
	membersList.push(new Member("MERCY MUEMUIFO"));
	membersList.push(new Member("MGBEZE JUDE"));
	membersList.push(new Member("MOKA A. GLORIA"));
	membersList.push(new Member("MONYE NNEKA JUDITH"));
	membersList.push(new Member("MOSES ONORIODE BRAGIWA"));
	membersList.push(new Member("MUDIAGA EJEWHOMU"));
	membersList.push(new Member("MUKORO O. B."));
	membersList.push(new Member("NDIDI SOLOMON"));
	membersList.push(new Member("NEWMAN RECHARDS"));
	membersList.push(new Member("NKEM AJUFO"));
	membersList.push(new Member("NKEZE A."));
	membersList.push(new Member("NKEZE CHINEDU"));
	membersList.push(new Member("NKWOR ANDREW"));
	membersList.push(new Member("NORRIS OVILI"));
	membersList.push(new Member("NTOSI JOY NGOZI"));
	membersList.push(new Member("NWAIFE HARRISON"));
	membersList.push(new Member("NWAMANA JOSEPH"));
	membersList.push(new Member("NWANDU ANTHONY E."));
	membersList.push(new Member("NWANZE J. N."));
	membersList.push(new Member("NWOKEDI ALEX"));
	membersList.push(new Member("OBIEBI OGHENEBRUME"));
	membersList.push(new Member("OBROTU P. E."));
	membersList.push(new Member("OBY OMUNIZUA AUGUSTINA"));
	membersList.push(new Member("ODIWE NGOZI"));
	membersList.push(new Member("ODUDURU - IKOLOMI TRACY"));
	membersList.push(new Member("OGBOGU STEVE"));
	membersList.push(new Member("OGBOLU E. N."));
	membersList.push(new Member("OGE GREAT NWOKWULE"));
	membersList.push(new Member("OGE-OGANA EKENE"));
	membersList.push(new Member("OKARO C. MAJIRI"));
	membersList.push(new Member("OKEGBE DELPHINE"));
	membersList.push(new Member("OKEY ORAEKE"));
	membersList.push(new Member("OKOBI MARY MEBELI"));
	membersList.push(new Member("OKOH EMMANUEL"));
	membersList.push(new Member("OKONKWO BENITA NNEKA"));
	membersList.push(new Member("OKOSUN ALERO"));
	membersList.push(new Member("OKOYE JOHNBOSCO EKENE"));
	membersList.push(new Member("OKPALA RITA"));
	membersList.push(new Member("OKPORUGO SUNDAY"));
	membersList.push(new Member("OKPUZOR GRACE O."));
	membersList.push(new Member("OKUMAGBA ERUTEYA"));
	membersList.push(new Member("OKUNBOR INNOCENT"));
	membersList.push(new Member("OKUNEYE ADA FAITH"));
	membersList.push(new Member("OKWONE PAT."));
	membersList.push(new Member("OKWUASABA CHARLES"));
	membersList.push(new Member("OLOROGUN BARR. JOSHUA O. MUWHEN"));
	membersList.push(new Member("OMEZI A. O. Esq."));
	membersList.push(new Member("OMONEMU BRIDGET"));
	membersList.push(new Member("OMOSHOLA SHADRACK"));
	membersList.push(new Member("OMUJALE, O. O."));
	membersList.push(new Member("ONOKPE D. A."));
	membersList.push(new Member("ONWUKA ANGELA"));
	membersList.push(new Member("ONYEISI NKENCHOR"));
	membersList.push(new Member("ONYIBE GLORIA"));
	membersList.push(new Member("ONYIBE VICTORY ISIOMA"));
	membersList.push(new Member("ORANYELI MICHAEL"));
	membersList.push(new Member("ORHERHE C. EFE"));
	membersList.push(new Member("ORIGBO A. O."));
	membersList.push(new Member("ORISHEDERE EJIROGHENE PRAISE"));
	membersList.push(new Member("OSADEBE ANNA"));
	membersList.push(new Member("OSISIOMA AMAKA"));
	membersList.push(new Member("OSSAI JERRY"));
	membersList.push(new Member("OWHOFADJEKE EJIRO"));
	membersList.push(new Member("PATIENCE N. ETUMUDON"));
	membersList.push(new Member("PETER N. OMAME"));
	membersList.push(new Member("PRECIOUS NWADIMUYA Esq."));
	membersList.push(new Member("PROF. LADY OKOH"));
	membersList.push(new Member("R. K. MORUKU"));
	membersList.push(new Member("REGINALD BAYOKO"));
	membersList.push(new Member("REX OVIE OHRE"));
	membersList.push(new Member("RITA OSSAI N."));
	membersList.push(new Member("ROSEMARY PORIAIMAH"));
	membersList.push(new Member("S. O. DIETEKE"));
	membersList.push(new Member("SAGE VINCENT I."));
	membersList.push(new Member("SAMAILA BAKARI BANGSHIKA"));
	membersList.push(new Member("SIMEON O. ONAKUGHOTOR"));
	membersList.push(new Member("SIMETEJE, C."));
	membersList.push(new Member("SIR DR. JERRY AGBAIKE"));
	membersList.push(new Member("SMART EDOGE"));
	membersList.push(new Member("SOBO PATRICIA ORITSUWA"));
	membersList.push(new Member("SOLOMON ADURAOSI"));
	membersList.push(new Member("STELLA F. S."));
	membersList.push(new Member("STELLA ITOKO"));
	membersList.push(new Member("STEPHEN EKUMME"));
	membersList.push(new Member("TEGA ADAIFEFUN"));
	membersList.push(new Member("THEODORA APIKI AKPORAIRI"));
	membersList.push(new Member("THEODORA E. G. EGBO"));
	membersList.push(new Member("UDI MERCY O."));
	membersList.push(new Member("UGBEJEH O. SAMSON"));
	membersList.push(new Member("UGBEN GODSPOWER"));
	membersList.push(new Member("UMMUNA P. M."));
	membersList.push(new Member("USHI ISRAEL"));
	membersList.push(new Member("UVIETSIRI MARK"));
	membersList.push(new Member("VOKE DINFE ETUOKWU"));
}
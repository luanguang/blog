SELECT id,email,verifiedMobile,nickname FROM `user` WHERE verifiedMobile in (select verifiedMobile from `user` where verifiedMobile <> '' group by verifiedMobile having count(verifiedMobile)>1)


select a.id,a.email,a.verifiedMobile,a.nickname from `user` a, (select verifiedMobile from `user` where verifiedMobile <> "" group by verifiedMobile having count(verifiedMobile) > 1) b where a.verifiedMobile = b.verifiedMobile
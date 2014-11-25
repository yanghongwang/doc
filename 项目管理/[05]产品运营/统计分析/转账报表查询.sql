select 
B.fd_author_id AS '用户的id',
CONCAT(B.fd_author_truename, '-', B.fd_author_username) AS '用户姓名',
A.fd_tfmglist_fucardno AS '付款卡号',
A.fd_tfmglist_fucardbank AS '付款开户行',
A.fd_tfmglist_shoucardno AS '收款卡号',
A.fd_tfmglist_shoucardman AS '收款人姓名',
A.fd_tfmglist_paydate AS '交易日期',
A.fd_tfmglist_paymoney AS '交易金额',
A.fd_tfmglist_payfee as '交易手续费' 
FROM 
tb_transfermoneyglist AS A LEFT JOIN tb_author AS B
 ON A.fd_tfmglist_authorid = B.fd_author_id 
WHERE  fd_tfmglist_paydate >= '2014-10-01' AND fd_tfmglist_paydate<= '2014-10-31'
 ORDER BY A.fd_tfmglist_paydate asc
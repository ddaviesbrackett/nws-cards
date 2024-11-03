-- utility script for collapsing two classes together. 
-- used in 2024 to fold all funds that aren't class funds (childcare, tuition reduction, etc) into the pac.
-- uses mysql bind parameters, replace : tokens with class ID values
-- :recipient_class_id is the class into which things are folded
-- :donor_class_id is the class that's being folded in

-- transfer the historical profits from the donor class to the recipient class
update classes_orders tgt
inner join classes_orders src on tgt.order_id = src.order_id
set tgt.profit = tgt.profit + src.profit
where tgt.class_id = :recipient_class_id and src.class_id = :donor_class_id;
-- delete the donor class profit records (their values are in the recipient values now)
delete from classes_orders where class_id = :donor_class_id;

-- repeat the above, for point sales
update classes_pointsales tgt
inner join classes_pointsales src on tgt.pointsale_id = src.pointsale_id
set tgt.profit = tgt.profit + src.profit
where tgt.class_id = :recipient_class_id and src.class_id = :donor_class_id;

delete from classes_pointsales where class_id = :donor_class_id;

-- and fold in expenses too
update expenses set class_id = :recipient_class_id  where class_id = :donor_class_id;


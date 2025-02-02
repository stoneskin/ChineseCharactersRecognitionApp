CREATE VIEW recent_ch_students AS
SELECT cls.class_name ,stu.first_name ,stu.last_name 
FROM Registration reg 
JOIN mlc_class cls ON reg.mlc_class_id = cls.id 
JOIN student stu ON reg.student_id = stu.id 
WHERE date_part('year', reg.create_date) >=  (date_part('year', current_date)-1)
AND ( class_name ILIKE '%CHL%'  OR class_name ILIKE '%CSL%')
ORDER BY class_name;
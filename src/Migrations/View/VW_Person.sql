DROP VIEW IF EXISTS VW_Person;

CREATE VIEW VW_Person AS

SELECT P.id                 as id,
       P.type               ,
       P.name               as person_name,
       P.nickname           ,
       P.observations       ,
       P.birth_date         ,
       P.is_active          ,
       P.is_customer        ,
       P.is_supplier        ,
       P.is_employee        ,
       PA.uf                ,
       PA.city              ,
       PA.address           ,
       PA.district          ,
       PA.address_complement,
       PA.number            ,
       PA.zip               ,
       PA.ibge_cidade       ,
       PC.contact_name      ,
       PC.phone             ,
       PC.email
FROM person P
         LEFT JOIN person_address PA ON P.main_address_id = PA.id
         LEFT JOIN person_contact PC ON P.main_contact_id = PC.id
ORDER BY P.id DESC

;
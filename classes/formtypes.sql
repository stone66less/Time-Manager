CREATE TABLE form_types (
lang_code    VARCHAR(5)   NOT NULL,
form_type    CHAR(1)      NOT NULL,
type_descr   VARCHAR(32)  NOT NULL,
inserted_by  INTEGER,
insert_time  TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
updated_by   INTEGER,
update_time  TIMESTAMP WITH TIME ZONE,
co_user_data VARCHAR(240),
au_vers_numb INTEGER DEFAULT 0,
CONSTRAINT fk_ft_1 FOREIGN KEY (lang_code) REFERENCES supported_languages (lang_code)
)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE UNIQUE INDEX i_ft_1 ON form_types (lang_code, form_type) TABLESPACE timeindex
;

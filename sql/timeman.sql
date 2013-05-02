CREATE TABLE supported_languages (
lang_code    VARCHAR(5)  NOT NULL PRIMARY KEY USING INDEX TABLESPACE timeindex,
lang_name    VARCHAR(40) NOT NULL,
lang_inuse   BOOLEAN     DEFAULT FALSE,
char_set     VARCHAR(16),
dir_ection   CHAR(1),
welcome_text VARCHAR(20),
farwell_text VARCHAR(20),
footer_text  VARCHAR(80),
yes_text     VARCHAR(16),
no_text      VARCHAR(16),
inserted_by  INTEGER,
insert_time  TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
updated_by   INTEGER,
update_time  TIMESTAMP WITH TIME ZONE,
co_user_data VARCHAR(240),
au_vers_numb INTEGER DEFAULT 0)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE TABLE group_roles (
gr_id        SERIAL NOT NULL PRIMARY KEY USING INDEX TABLESPACE timeindex,
gr_name      VARCHAR(60)  NOT NULL,
super_user   BOOLEAN  DEFAULT FALSE,
view_others  BOOLEAN  DEFAULT FALSE,
group_inuse  BOOLEAN  DEFAULT FALSE,
chg_pword    SMALLINT DEFAULT 0,
anniv_limit  SMALLINT,
appnt_limit  SMALLINT,
tasks_limit  SMALLINT,
sysadm_lmt   SMALLINT,
inserted_by  INTEGER,
insert_time  TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
updated_by   INTEGER,
update_time  TIMESTAMP WITH TIME ZONE,
co_user_data VARCHAR(240),
au_vers_numb INTEGER DEFAULT 0)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE TABLE time_users (
tu_id        SERIAL   NOT NULL PRIMARY KEY USING INDEX TABLESPACE timeindex,
logon_id     VARCHAR(4)  NOT NULL,
logon_name   VARCHAR(60) NOT NULL,
gr_id        INTEGER     NOT NULL,
lang_code    VARCHAR(5)  NOT NULL,
active_user  BOOLEAN DEFAULT FALSE,
super_user   BOOLEAN DEFAULT FALSE,
sysgrp_user  BOOLEAN DEFAULT FALSE,
fixed_ip     INET,
pass_word    VARCHAR(128),
utc_offset   SMALLINT,
phone_extn   SMALLINT,
email_addr   VARCHAR(256),
last_pword   DATE,
inserted_by  INTEGER,
insert_time  TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
updated_by   INTEGER,
update_time  TIMESTAMP WITH TIME ZONE,
co_user_data VARCHAR(240),
au_vers_numb INTEGER DEFAULT 0,
CONSTRAINT fk_tu_1 FOREIGN KEY (gr_id) REFERENCES group_roles (gr_id),
CONSTRAINT fk_tu_2 FOREIGN KEY (lang_code) REFERENCES supported_languages (lang_code)
)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE UNIQUE INDEX i_tu_1 ON time_users (logon_id) TABLESPACE timeindex
;
CREATE TABLE logged_users (
lu_id        SERIAL  NOT NULL PRIMARY KEY USING INDEX TABLESPACE timeindex,
tu_id        INTEGER NOT NULL,
ip_address   VARCHAR(40) NOT NULL,
logon_time   TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
logoff_time  TIMESTAMP WITH TIME ZONE,
co_user_data VARCHAR(240),
au_vers_numb INTEGER DEFAULT 0,
CONSTRAINT fk_lu_1 FOREIGN KEY (tu_id) REFERENCES time_users (tu_id)
)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE UNIQUE INDEX i_lu_1 ON logged_users (tu_id, lu_id) TABLESPACE timeindex
;
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
CREATE TABLE forms_menu (
fm_id        SERIAL    NOT NULL PRIMARY KEY USING INDEX TABLESPACE timeindex,
navgn_refn   SMALLINT  NOT NULL,
form_name    VARCHAR(40) NOT NULL,
active_item  BOOLEAN   DEFAULT FALSE,
super_user   BOOLEAN   DEFAULT FALSE,
sysgrp_user  BOOLEAN   DEFAULT FALSE,
form_type    CHAR(1)   NOT NULL,
navgn_bar    VARCHAR(160),
forward_to   SMALLINT,
second_to    SMALLINT,
inserted_by  INTEGER,
insert_time  TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
updated_by   INTEGER,
update_time  TIMESTAMP WITH TIME ZONE,
co_user_data VARCHAR(240),
au_vers_numb INTEGER DEFAULT 0
)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE UNIQUE INDEX i_fm_1 ON forms_menu (navgn_refn) TABLESPACE timeindex
;
CREATE TABLE boiler_plate (
bp_id        SERIAL     NOT NULL PRIMARY KEY USING INDEX TABLESPACE timeindex,
navgn_refn   SMALLINT   NOT NULL,
lang_code    VARCHAR(5) NOT NULL,
page_title   VARCHAR(80),
heading_one  VARCHAR(80),
heading_two  VARCHAR(80),
heading_tre  VARCHAR(80),
heading_qua  VARCHAR(80),
heading_cin  VARCHAR(80),
heading_six  VARCHAR(80),
navign_bar   VARCHAR(400),
capt_ions    VARCHAR(80),
thtd_cells   VARCHAR(400),
leg_end      VARCHAR(80),
form_fields  VARCHAR(600),
subt_buttons VARCHAR(400),
inserted_by  INTEGER,
insert_time  TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
updated_by   INTEGER,
update_time  TIMESTAMP WITH TIME ZONE,
co_user_data VARCHAR(240),
au_vers_numb INTEGER DEFAULT 0,
CONSTRAINT fk_bp_1 FOREIGN KEY (navgn_refn) REFERENCES forms_menu (navgn_refn),
CONSTRAINT fk_bp_2 FOREIGN KEY (lang_code) REFERENCES supported_languages (lang_code)
)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE UNIQUE INDEX i_bp_1 ON boiler_plate (navgn_refn, lang_code) TABLESPACE timeindex
;
CREATE TABLE anni_versaries (
av_id        SERIAL  NOT NULL PRIMARY KEY USING INDEX TABLESPACE timeindex,
tu_id        INTEGER   NOT NULL,
anni_descr   VARCHAR(128)  NOT NULL,
anni_tday    SMALLINT   NOT NULL,
anni_month   SMALLINT   NOT NULL,
anni_sday    SMALLINT   NOT NULL,
anni_active  BOOLEAN    DEFAULT TRUE,
inserted_by  INTEGER,
insert_time  TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
updated_by   INTEGER,
update_time  TIMESTAMP WITH TIME ZONE,
co_user_data VARCHAR(240),
au_vers_numb INTEGER DEFAULT 0,
CONSTRAINT fk_av_1 FOREIGN KEY (tu_id) REFERENCES time_users (tu_id)
)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE UNIQUE INDEX i_av_1 ON anni_versaries (tu_id, av_id) TABLESPACE timeindex
;
CREATE TABLE appoint_ments (
ap_id        SERIAL  NOT NULL PRIMARY KEY USING INDEX TABLESPACE timeindex,
tu_id        INTEGER    NOT NULL,
appoint_date DATE       NOT NULL,
appoint_hour SMALLINT   NOT NULL,
appoint_min  SMALLINT   NOT NULL,
with_whom    VARCHAR(128),
meet_subjt   VARCHAR(128),
est_drtn     SMALLINT,
depart_time  SMALLINT,
intl_meet    BOOLEAN    DEFAULT FALSE,
meet_where   VARCHAR(128),
meet_cancld  BOOLEAN    DEFAULT FALSE,
inserted_by  INTEGER,
insert_time  TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
updated_by   INTEGER,
update_time  TIMESTAMP WITH TIME ZONE,
co_user_data VARCHAR(240),
au_vers_numb INTEGER DEFAULT 0,
CONSTRAINT fk_ap_1 FOREIGN KEY (tu_id) REFERENCES time_users (tu_id)
)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE UNIQUE INDEX i_ap_1 ON appoint_ments (tu_id, ap_id) TABLESPACE timeindex
;
CREATE TABLE all_tasks (
allt_id      SERIAL  NOT NULL PRIMARY KEY USING INDEX TABLESPACE timeindex,
tu_id        INTEGER    NOT NULL,
task_class   CHAR(1)    NOT NULL,
sequence_no  SMALLINT   NOT NULL DEFAULT 99,
todo_date    DATE       NOT NULL,
task_descrn  VARCHAR(256) NOT NULL,
task_compl   BOOLEAN    DEFAULT FALSE,
task_resched BOOLEAN    DEFAULT FALSE,
task_copied  BOOLEAN    DEFAULT FALSE,
resch_count  INTEGER    DEFAULT 0,
inserted_by  INTEGER,
insert_time  TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
updated_by   INTEGER,
update_time  TIMESTAMP WITH TIME ZONE,
co_user_data VARCHAR(240),
au_vers_numb INTEGER DEFAULT 0,
CONSTRAINT fk_at_1 FOREIGN KEY (tu_id) REFERENCES time_users (tu_id)
)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE UNIQUE INDEX i_allt_1 ON all_tasks (tu_id, allt_id) TABLESPACE timeindex
;
CREATE TABLE error_messages (
erm_id        SERIAL       NOT NULL PRIMARY KEY USING INDEX TABLESPACE timeindex,
error_number  INTEGER      NOT NULL,
lang_code     VARCHAR(5)   NOT NULL,
error_messg   VARCHAR(80)  NOT NULL,
error_help    VARCHAR(600),
inserted_by   INTEGER,
insert_time   TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
updated_by    INTEGER,
update_time   TIMESTAMP WITH TIME ZONE,
co_user_data  VARCHAR(240),
au_vers_numb  INTEGER DEFAULT 0,
CONSTRAINT fk_em_1 FOREIGN KEY (lang_code) REFERENCES supported_languages (lang_code)
)
WITH(OIDS=FALSE)
TABLESPACE timedata
;
CREATE UNIQUE INDEX i_em_1 ON error_messages (error_number, lang_code) TABLESPACE timeindex
;
CREATE VIEW user_group_lang (
tu_id, logon_id, logon_name,
active_user, sysgrp_user, lang_code,
gr_id, gr_name, view_others,
lang_name
) AS
SELECT TU.tu_id, TU.logon_id, TU.logon_name,
TU.active_user, TU.sysgrp_user, TU.lang_code,
GR.gr_id, GR.gr_name, GR.view_others,
SL.lang_name
FROM supported_languages SL,
	group_roles GR,
	time_users TU
WHERE TU.gr_id = GR.gr_id
AND   TU.lang_code = SL.lang_code
;
INSERT INTO supported_languages
(lang_code, lang_name, lang_inuse, char_set, dir_ection, welcome_text,
farwell_text, footer_text, yes_text, no_text, inserted_by)
VALUES ('en-GB','British English',TRUE,'UTF-8','L', 'Welcome', 'Farewell',
'Copyright &copy;2013 Robert M. Stone. All rights reserved.', 'Yes', 'No', 0)
;
INSERT INTO supported_languages
(lang_code, lang_name, lang_inuse, char_set, dir_ection, welcome_text,
yes_text, no_text, inserted_by)
VALUES ('fr','Fran&ccedil;ais',TRUE,'UTF-8','L','Bienvenue',
'Oui', 'Non', 0)
;
INSERT INTO supported_languages
(lang_code, lang_name, lang_inuse, char_set, dir_ection, welcome_text,
yes_text, no_text, inserted_by)
VALUES ('de','Deutsch',TRUE,'UTF-8','L','Willkommen',
'Ja', 'Nein', 0)
;
INSERT INTO group_roles
(gr_name, super_user,
view_others, chg_pword,
sysadm_lmt, inserted_by)
values ('System Administration Group', TRUE,
TRUE, 1, 10, 0)
;
INSERT INTO time_users
(logon_id, logon_name,
gr_id, active_user, super_user,
lang_code, inserted_by)
values ('SYS', 'System User',
1, TRUE, TRUE,
'en-GB',0)
;

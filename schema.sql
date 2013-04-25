DROP TABLE IF EXISTS public.diffs;
CREATE TABLE public.diffs
(
	diff_id serial NOT NULL,
	username varchar(50),
	password varchar(250),
	comment text,
	notify_address varchar(250),
	parent_diff_id integer NULL,
	created timestamp NOT NULL,

	CONSTRAINT diffs_pkey PRIMARY KEY (diff_id)
);

DROP TABLE IF EXISTS public.comments;
CREATE TABLE public.comments
(
	comment_id serial NOT NULL,
	diff_id integer NOT NULL,
	file varchar(255) NOT NULL,
	line_number integer NOT NULL,
	username varchar(255),
	comment text,
	created timestamp,
CONSTRAINT comments_pkey PRIMARY KEY (comment_id)
);

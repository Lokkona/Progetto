--
-- PostgreSQL database dump
--

-- Dumped from database version 17.2
-- Dumped by pg_dump version 17.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: utente; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.utente (
    id integer NOT NULL,
    nome character varying(100) NOT NULL,
    uname character varying(100) NOT NULL,
    mail character varying(100) NOT NULL,
    pass character varying(100) NOT NULL,
    fpass character varying(100) NOT NULL
);


ALTER TABLE public.utente OWNER TO postgres;

--
-- Name: utente_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.utente_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.utente_id_seq OWNER TO postgres;

--
-- Name: utente_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.utente_id_seq OWNED BY public.utente.id;


--
-- Name: utente id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utente ALTER COLUMN id SET DEFAULT nextval('public.utente_id_seq'::regclass);


--
-- Data for Name: utente; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.utente (id, nome, uname, mail, pass, fpass) FROM stdin;
19	Eraldo	Lokkona03	eraldo.imbriani@gmail.com	$2y$10$NmL.BgmIBwoBtiiyVAsXvu7oNYLytiqWM8z34i4Y5g3jmXqKQeP96	1Nf4m303
20	Marino	Lokkona	marino@gmail.co	$2y$10$rznroKv0NLYjO3YiYvEjcOM8Jfo0SfnnvQFtlxjgHAvdxxMJ7rslO	1Nf4m3
21	Eraldo	obbiv	vincenzo@gmail.co	$2y$10$JuO6/gU4./IZ24u7M5D1euRugLoirpBLJrqYfNEXhXcpDDRd9ghbW	1Nf4m3
\.


--
-- Name: utente_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.utente_id_seq', 21, true);


--
-- Name: utente utente_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utente
    ADD CONSTRAINT utente_pkey PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--


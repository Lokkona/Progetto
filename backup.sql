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
-- Name: prestazioni; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.prestazioni (
    id integer NOT NULL,
    user_id integer,
    sport character varying(20) NOT NULL,
    data date NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT prestazioni_sport_check CHECK (((sport)::text = ANY ((ARRAY['basket'::character varying, 'calcio'::character varying, 'tennis'::character varying])::text[])))
);


ALTER TABLE public.prestazioni OWNER TO postgres;

--
-- Name: prestazioni_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.prestazioni_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.prestazioni_id_seq OWNER TO postgres;

--
-- Name: prestazioni_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.prestazioni_id_seq OWNED BY public.prestazioni.id;


--
-- Name: stats_basket; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.stats_basket (
    id integer NOT NULL,
    prestazione_id integer,
    minuti integer,
    punti integer,
    tiri_tentati integer,
    tiri_realizzati integer,
    tiri3_tentati integer,
    tiri3_realizzati integer,
    tiri_liberi_tentati integer,
    tiri_liberi_realizzati integer,
    rimbalzi_totali integer,
    rimbalzi_offensivi integer,
    rimbalzi_difensivi integer,
    assist integer,
    stoppate integer,
    palle_rubate integer,
    palle_perse integer,
    CONSTRAINT check_liberi CHECK ((tiri_liberi_realizzati <= tiri_liberi_tentati)),
    CONSTRAINT check_rimbalzi CHECK (((rimbalzi_offensivi + rimbalzi_difensivi) = rimbalzi_totali)),
    CONSTRAINT check_tiri CHECK ((tiri_realizzati <= tiri_tentati)),
    CONSTRAINT check_tiri3 CHECK ((tiri3_realizzati <= tiri3_tentati)),
    CONSTRAINT stats_basket_assist_check CHECK ((assist >= 0)),
    CONSTRAINT stats_basket_minuti_check CHECK ((minuti >= 0)),
    CONSTRAINT stats_basket_palle_perse_check CHECK ((palle_perse >= 0)),
    CONSTRAINT stats_basket_palle_rubate_check CHECK ((palle_rubate >= 0)),
    CONSTRAINT stats_basket_punti_check CHECK ((punti >= 0)),
    CONSTRAINT stats_basket_rimbalzi_difensivi_check CHECK ((rimbalzi_difensivi >= 0)),
    CONSTRAINT stats_basket_rimbalzi_offensivi_check CHECK ((rimbalzi_offensivi >= 0)),
    CONSTRAINT stats_basket_rimbalzi_totali_check CHECK ((rimbalzi_totali >= 0)),
    CONSTRAINT stats_basket_stoppate_check CHECK ((stoppate >= 0)),
    CONSTRAINT stats_basket_tiri3_realizzati_check CHECK ((tiri3_realizzati >= 0)),
    CONSTRAINT stats_basket_tiri3_tentati_check CHECK ((tiri3_tentati >= 0)),
    CONSTRAINT stats_basket_tiri_liberi_realizzati_check CHECK ((tiri_liberi_realizzati >= 0)),
    CONSTRAINT stats_basket_tiri_liberi_tentati_check CHECK ((tiri_liberi_tentati >= 0)),
    CONSTRAINT stats_basket_tiri_realizzati_check CHECK ((tiri_realizzati >= 0)),
    CONSTRAINT stats_basket_tiri_tentati_check CHECK ((tiri_tentati >= 0))
);


ALTER TABLE public.stats_basket OWNER TO postgres;

--
-- Name: stats_basket_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.stats_basket_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.stats_basket_id_seq OWNER TO postgres;

--
-- Name: stats_basket_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.stats_basket_id_seq OWNED BY public.stats_basket.id;


--
-- Name: stats_calcio; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.stats_calcio (
    id integer NOT NULL,
    prestazione_id integer,
    minuti integer,
    gol integer,
    tiri integer,
    tiri_porta integer,
    assist integer,
    passaggi_tentati integer,
    passaggi_riusciti integer,
    intercetti integer,
    contrasti integer,
    palle_recuperate integer,
    dribbling_tentati integer,
    dribbling_riusciti integer,
    CONSTRAINT check_dribbling CHECK ((dribbling_riusciti <= dribbling_tentati)),
    CONSTRAINT check_passaggi CHECK ((passaggi_riusciti <= passaggi_tentati)),
    CONSTRAINT check_tiri_porta CHECK ((tiri_porta <= tiri)),
    CONSTRAINT stats_calcio_assist_check CHECK ((assist >= 0)),
    CONSTRAINT stats_calcio_contrasti_check CHECK ((contrasti >= 0)),
    CONSTRAINT stats_calcio_dribbling_riusciti_check CHECK ((dribbling_riusciti >= 0)),
    CONSTRAINT stats_calcio_dribbling_tentati_check CHECK ((dribbling_tentati >= 0)),
    CONSTRAINT stats_calcio_gol_check CHECK ((gol >= 0)),
    CONSTRAINT stats_calcio_intercetti_check CHECK ((intercetti >= 0)),
    CONSTRAINT stats_calcio_minuti_check CHECK ((minuti >= 0)),
    CONSTRAINT stats_calcio_palle_recuperate_check CHECK ((palle_recuperate >= 0)),
    CONSTRAINT stats_calcio_passaggi_riusciti_check CHECK ((passaggi_riusciti >= 0)),
    CONSTRAINT stats_calcio_passaggi_tentati_check CHECK ((passaggi_tentati >= 0)),
    CONSTRAINT stats_calcio_tiri_check CHECK ((tiri >= 0)),
    CONSTRAINT stats_calcio_tiri_porta_check CHECK ((tiri_porta >= 0))
);


ALTER TABLE public.stats_calcio OWNER TO postgres;

--
-- Name: stats_calcio_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.stats_calcio_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.stats_calcio_id_seq OWNER TO postgres;

--
-- Name: stats_calcio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.stats_calcio_id_seq OWNED BY public.stats_calcio.id;


--
-- Name: stats_tennis; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.stats_tennis (
    id integer NOT NULL,
    prestazione_id integer,
    tempo integer,
    punti_giocati integer,
    punti_vinti integer,
    prima_giocate integer,
    prima_campo integer,
    prima_vinte integer,
    seconda_campo integer,
    seconda_vinte integer,
    doppi_falli integer,
    risposta_giocati integer,
    risposta_vinti integer,
    break_punti integer,
    break_convertiti integer,
    errori integer,
    CONSTRAINT check_break CHECK ((break_convertiti <= break_punti)),
    CONSTRAINT check_prima CHECK (((prima_campo <= prima_giocate) AND (prima_vinte <= prima_campo))),
    CONSTRAINT check_punti CHECK ((punti_vinti <= punti_giocati)),
    CONSTRAINT check_seconda CHECK ((seconda_vinte <= seconda_campo)),
    CONSTRAINT stats_tennis_break_convertiti_check CHECK ((break_convertiti >= 0)),
    CONSTRAINT stats_tennis_break_punti_check CHECK ((break_punti >= 0)),
    CONSTRAINT stats_tennis_doppi_falli_check CHECK ((doppi_falli >= 0)),
    CONSTRAINT stats_tennis_errori_check CHECK ((errori >= 0)),
    CONSTRAINT stats_tennis_prima_campo_check CHECK ((prima_campo >= 0)),
    CONSTRAINT stats_tennis_prima_giocate_check CHECK ((prima_giocate >= 0)),
    CONSTRAINT stats_tennis_prima_vinte_check CHECK ((prima_vinte >= 0)),
    CONSTRAINT stats_tennis_punti_giocati_check CHECK ((punti_giocati >= 0)),
    CONSTRAINT stats_tennis_punti_vinti_check CHECK ((punti_vinti >= 0)),
    CONSTRAINT stats_tennis_risposta_giocati_check CHECK ((risposta_giocati >= 0)),
    CONSTRAINT stats_tennis_risposta_vinti_check CHECK ((risposta_vinti >= 0)),
    CONSTRAINT stats_tennis_seconda_campo_check CHECK ((seconda_campo >= 0)),
    CONSTRAINT stats_tennis_seconda_vinte_check CHECK ((seconda_vinte >= 0)),
    CONSTRAINT stats_tennis_tempo_check CHECK ((tempo >= 0))
);


ALTER TABLE public.stats_tennis OWNER TO postgres;

--
-- Name: stats_tennis_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.stats_tennis_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.stats_tennis_id_seq OWNER TO postgres;

--
-- Name: stats_tennis_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.stats_tennis_id_seq OWNED BY public.stats_tennis.id;


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
-- Name: prestazioni id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prestazioni ALTER COLUMN id SET DEFAULT nextval('public.prestazioni_id_seq'::regclass);


--
-- Name: stats_basket id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats_basket ALTER COLUMN id SET DEFAULT nextval('public.stats_basket_id_seq'::regclass);


--
-- Name: stats_calcio id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats_calcio ALTER COLUMN id SET DEFAULT nextval('public.stats_calcio_id_seq'::regclass);


--
-- Name: stats_tennis id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats_tennis ALTER COLUMN id SET DEFAULT nextval('public.stats_tennis_id_seq'::regclass);


--
-- Name: utente id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utente ALTER COLUMN id SET DEFAULT nextval('public.utente_id_seq'::regclass);


--
-- Data for Name: prestazioni; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.prestazioni (id, user_id, sport, data, created_at) FROM stdin;
\.


--
-- Data for Name: stats_basket; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.stats_basket (id, prestazione_id, minuti, punti, tiri_tentati, tiri_realizzati, tiri3_tentati, tiri3_realizzati, tiri_liberi_tentati, tiri_liberi_realizzati, rimbalzi_totali, rimbalzi_offensivi, rimbalzi_difensivi, assist, stoppate, palle_rubate, palle_perse) FROM stdin;
\.


--
-- Data for Name: stats_calcio; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.stats_calcio (id, prestazione_id, minuti, gol, tiri, tiri_porta, assist, passaggi_tentati, passaggi_riusciti, intercetti, contrasti, palle_recuperate, dribbling_tentati, dribbling_riusciti) FROM stdin;
\.


--
-- Data for Name: stats_tennis; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.stats_tennis (id, prestazione_id, tempo, punti_giocati, punti_vinti, prima_giocate, prima_campo, prima_vinte, seconda_campo, seconda_vinte, doppi_falli, risposta_giocati, risposta_vinti, break_punti, break_convertiti, errori) FROM stdin;
\.


--
-- Data for Name: utente; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.utente (id, nome, uname, mail, pass, fpass) FROM stdin;
19	Eraldo	Lokkona03	eraldo.imbriani@gmail.com	$2y$10$NmL.BgmIBwoBtiiyVAsXvu7oNYLytiqWM8z34i4Y5g3jmXqKQeP96	1Nf4m303
20	Marino	Lokkona	marino@gmail.co	$2y$10$rznroKv0NLYjO3YiYvEjcOM8Jfo0SfnnvQFtlxjgHAvdxxMJ7rslO	1Nf4m3
21	Eraldo	obbiv	vincenzo@gmail.co	$2y$10$JuO6/gU4./IZ24u7M5D1euRugLoirpBLJrqYfNEXhXcpDDRd9ghbW	1Nf4m3
22	Eraldo	Picardi	eraldo@gmail.com	$2y$10$RDxF8mvSxgDemamEnLlL6.6Au2YNtX..XvP3zKgj1k1r/QEVfydkG	pizzo
\.


--
-- Name: prestazioni_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.prestazioni_id_seq', 1, false);


--
-- Name: stats_basket_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.stats_basket_id_seq', 1, false);


--
-- Name: stats_calcio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.stats_calcio_id_seq', 1, false);


--
-- Name: stats_tennis_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.stats_tennis_id_seq', 1, false);


--
-- Name: utente_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.utente_id_seq', 22, true);


--
-- Name: prestazioni prestazioni_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prestazioni
    ADD CONSTRAINT prestazioni_pkey PRIMARY KEY (id);


--
-- Name: stats_basket stats_basket_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats_basket
    ADD CONSTRAINT stats_basket_pkey PRIMARY KEY (id);


--
-- Name: stats_calcio stats_calcio_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats_calcio
    ADD CONSTRAINT stats_calcio_pkey PRIMARY KEY (id);


--
-- Name: stats_tennis stats_tennis_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats_tennis
    ADD CONSTRAINT stats_tennis_pkey PRIMARY KEY (id);


--
-- Name: utente utente_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utente
    ADD CONSTRAINT utente_pkey PRIMARY KEY (id);


--
-- Name: prestazioni prestazioni_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prestazioni
    ADD CONSTRAINT prestazioni_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.utente(id);


--
-- Name: stats_basket stats_basket_prestazione_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats_basket
    ADD CONSTRAINT stats_basket_prestazione_id_fkey FOREIGN KEY (prestazione_id) REFERENCES public.prestazioni(id) ON DELETE CASCADE;


--
-- Name: stats_calcio stats_calcio_prestazione_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats_calcio
    ADD CONSTRAINT stats_calcio_prestazione_id_fkey FOREIGN KEY (prestazione_id) REFERENCES public.prestazioni(id) ON DELETE CASCADE;


--
-- Name: stats_tennis stats_tennis_prestazione_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats_tennis
    ADD CONSTRAINT stats_tennis_prestazione_id_fkey FOREIGN KEY (prestazione_id) REFERENCES public.prestazioni(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--


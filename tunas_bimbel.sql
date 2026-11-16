--
-- PostgreSQL database dump
--

\restrict QeCFD1lOz9O65CNshIFaWnoKMbmQqheVB1SD6YSGQWZeyCbWx7OIsSoIanqpU0x

-- Dumped from database version 18.2
-- Dumped by pg_dump version 18.2

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
-- Name: announcements; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.announcements (
    id bigint NOT NULL,
    category character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    is_new boolean DEFAULT true NOT NULL,
    published_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: announcements_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.announcements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: announcements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.announcements_id_seq OWNED BY public.announcements.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: calendar_events; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.calendar_events (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    event_date date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: calendar_events_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.calendar_events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: calendar_events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.calendar_events_id_seq OWNED BY public.calendar_events.id;


--
-- Name: chapters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.chapters (
    id bigint NOT NULL,
    class_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: chapters_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.chapters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: chapters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.chapters_id_seq OWNED BY public.chapters.id;


--
-- Name: class_teacher; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.class_teacher (
    id bigint NOT NULL,
    class_id bigint NOT NULL,
    teacher_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: class_teacher_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.class_teacher_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: class_teacher_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.class_teacher_id_seq OWNED BY public.class_teacher.id;


--
-- Name: classes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.classes (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: classes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.classes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: classes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.classes_id_seq OWNED BY public.classes.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: linkeds; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.linkeds (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    url text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: linkeds_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.linkeds_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: linkeds_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.linkeds_id_seq OWNED BY public.linkeds.id;


--
-- Name: materi_pdfs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.materi_pdfs (
    id bigint NOT NULL,
    chapter_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    pdf_url character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    storage_type character varying(30) DEFAULT 'external_url'::character varying NOT NULL,
    file_mime_type character varying(100),
    file_size bigint
);


--
-- Name: materi_pdfs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.materi_pdfs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: materi_pdfs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.materi_pdfs_id_seq OWNED BY public.materi_pdfs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: package_classes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.package_classes (
    id bigint NOT NULL,
    package_id bigint NOT NULL,
    class_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: package_classes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.package_classes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: package_classes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.package_classes_id_seq OWNED BY public.package_classes.id;


--
-- Name: packages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.packages (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    price integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    image character varying(255)
);


--
-- Name: packages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.packages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: packages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.packages_id_seq OWNED BY public.packages.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: payment_methods; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.payment_methods (
    id bigint NOT NULL,
    name character varying(100) NOT NULL,
    type character varying(30) NOT NULL,
    provider character varying(100),
    account_name character varying(150),
    account_number character varying(100),
    qr_image character varying(255),
    instructions text,
    mode character varying(20) DEFAULT 'manual'::character varying NOT NULL,
    gateway_provider character varying(50),
    requires_proof boolean DEFAULT true NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: payment_methods_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.payment_methods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payment_methods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.payment_methods_id_seq OWNED BY public.payment_methods.id;


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name text NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: profile_siswas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.profile_siswas (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    name character varying(255),
    phone character varying(255),
    gender character varying(255),
    province_id character varying(255),
    regency_id character varying(255),
    district_id character varying(255),
    village_id character varying(255),
    postal_code character varying(255),
    avatar character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT profile_siswas_gender_check CHECK (((gender)::text = ANY ((ARRAY['pria'::character varying, 'wanita'::character varying])::text[])))
);


--
-- Name: profile_siswas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.profile_siswas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: profile_siswas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.profile_siswas_id_seq OWNED BY public.profile_siswas.id;


--
-- Name: questions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.questions (
    id bigint NOT NULL,
    quiz_id bigint NOT NULL,
    question text NOT NULL,
    option_a text NOT NULL,
    option_b text NOT NULL,
    option_c text NOT NULL,
    option_d text NOT NULL,
    correct_answer character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    image character varying(255)
);


--
-- Name: questions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.questions_id_seq OWNED BY public.questions.id;


--
-- Name: quiz_attempts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.quiz_attempts (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    token uuid,
    user_id bigint,
    quiz_id bigint,
    status character varying(20) DEFAULT 'active'::character varying NOT NULL,
    started_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    submitted_at timestamp(0) without time zone
);


--
-- Name: quiz_attempts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.quiz_attempts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: quiz_attempts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.quiz_attempts_id_seq OWNED BY public.quiz_attempts.id;


--
-- Name: quiz_results; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.quiz_results (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    quiz_id bigint NOT NULL,
    score integer NOT NULL,
    correct integer NOT NULL,
    wrong integer NOT NULL,
    empty integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    answers json
);


--
-- Name: quiz_results_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.quiz_results_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: quiz_results_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.quiz_results_id_seq OWNED BY public.quiz_results.id;


--
-- Name: quizzes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.quizzes (
    id bigint NOT NULL,
    class_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    duration integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: quizzes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.quizzes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: quizzes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.quizzes_id_seq OWNED BY public.quizzes.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: soal_attempts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.soal_attempts (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    token uuid,
    user_id bigint,
    soal_set_id bigint,
    status character varying(20) DEFAULT 'active'::character varying NOT NULL,
    started_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    submitted_at timestamp(0) without time zone
);


--
-- Name: soal_attempts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.soal_attempts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: soal_attempts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.soal_attempts_id_seq OWNED BY public.soal_attempts.id;


--
-- Name: soal_options; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.soal_options (
    id bigint NOT NULL,
    soal_question_id bigint NOT NULL,
    key character varying(255) NOT NULL,
    text text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: soal_options_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.soal_options_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: soal_options_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.soal_options_id_seq OWNED BY public.soal_options.id;


--
-- Name: soal_questions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.soal_questions (
    id bigint NOT NULL,
    soal_set_id bigint NOT NULL,
    question text NOT NULL,
    correct_answer character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: soal_questions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.soal_questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: soal_questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.soal_questions_id_seq OWNED BY public.soal_questions.id;


--
-- Name: soal_results; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.soal_results (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    soal_set_id bigint NOT NULL,
    score integer NOT NULL,
    correct integer NOT NULL,
    wrong integer NOT NULL,
    empty integer NOT NULL,
    answers json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: soal_results_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.soal_results_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: soal_results_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.soal_results_id_seq OWNED BY public.soal_results.id;


--
-- Name: soal_sections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.soal_sections (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    class_id bigint NOT NULL
);


--
-- Name: soal_sections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.soal_sections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: soal_sections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.soal_sections_id_seq OWNED BY public.soal_sections.id;


--
-- Name: soal_sets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.soal_sets (
    id bigint NOT NULL,
    soal_section_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    total_questions integer DEFAULT 0 NOT NULL,
    duration integer NOT NULL,
    points integer DEFAULT 0 NOT NULL,
    badge character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: soal_sets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.soal_sets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: soal_sets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.soal_sets_id_seq OWNED BY public.soal_sets.id;


--
-- Name: students; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.students (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    phone character varying(255),
    school character varying(255),
    grade character varying(255),
    address text,
    birth_date date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.students_id_seq OWNED BY public.students.id;


--
-- Name: teachers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.teachers (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    phone character varying(255),
    company character varying(255),
    specialization character varying(255),
    experience_years integer,
    bio text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: teachers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.teachers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: teachers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.teachers_id_seq OWNED BY public.teachers.id;


--
-- Name: transactions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.transactions (
    id bigint NOT NULL,
    invoice_no character varying(50) NOT NULL,
    public_token uuid NOT NULL,
    user_id bigint,
    package_id bigint,
    payment_method_id bigint,
    package_name character varying(150) NOT NULL,
    package_base_price numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    billing character varying(20) NOT NULL,
    duration_months smallint DEFAULT '1'::smallint NOT NULL,
    subtotal numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    discount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    total numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    customer_name character varying(150) NOT NULL,
    customer_email character varying(150) NOT NULL,
    customer_phone character varying(30) NOT NULL,
    payment_method_name character varying(150),
    payment_provider character varying(100),
    payment_account_name character varying(150),
    payment_account_number character varying(150),
    payment_mode character varying(20) DEFAULT 'manual'::character varying NOT NULL,
    gateway_provider character varying(50),
    gateway_reference character varying(150),
    gateway_payload json,
    status character varying(40) DEFAULT 'pending_payment'::character varying NOT NULL,
    proof_path character varying(255),
    proof_original_name character varying(255),
    proof_mime_type character varying(100),
    proof_uploaded_at timestamp(0) without time zone,
    reviewed_by bigint,
    reviewed_at timestamp(0) without time zone,
    rejection_reason text,
    paid_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transactions_id_seq OWNED BY public.transactions.id;


--
-- Name: user_learning_progress; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_learning_progress (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    chapter_id bigint NOT NULL,
    video_id bigint,
    pdf_id bigint,
    quiz_id bigint,
    status boolean DEFAULT false NOT NULL,
    progress_percent integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    completed_video_ids jsonb DEFAULT '[]'::jsonb NOT NULL,
    completed_pdf_ids jsonb DEFAULT '[]'::jsonb NOT NULL,
    completed_quiz_ids jsonb DEFAULT '[]'::jsonb NOT NULL
);


--
-- Name: user_learning_progress_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_learning_progress_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_learning_progress_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.user_learning_progress_id_seq OWNED BY public.user_learning_progress.id;


--
-- Name: user_packages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_packages (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    package_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    transaction_id bigint,
    status character varying(20) DEFAULT 'active'::character varying NOT NULL,
    activated_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone
);


--
-- Name: user_packages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_packages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_packages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.user_packages_id_seq OWNED BY public.user_packages.id;


--
-- Name: user_soal_progress; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_soal_progress (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    soal_set_id bigint NOT NULL,
    status boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: user_soal_progress_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_soal_progress_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_soal_progress_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.user_soal_progress_id_seq OWNED BY public.user_soal_progress.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'student'::character varying NOT NULL,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['administrator'::character varying, 'admin'::character varying, 'teacher'::character varying, 'student'::character varying])::text[])))
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: videos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.videos (
    id bigint NOT NULL,
    chapter_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    youtube_id character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    subtitle character varying(255),
    source_type character varying(30) DEFAULT 'youtube'::character varying NOT NULL,
    video_path character varying(255),
    video_mime_type character varying(100),
    video_size bigint
);


--
-- Name: videos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.videos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: videos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.videos_id_seq OWNED BY public.videos.id;


--
-- Name: announcements id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.announcements ALTER COLUMN id SET DEFAULT nextval('public.announcements_id_seq'::regclass);


--
-- Name: calendar_events id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.calendar_events ALTER COLUMN id SET DEFAULT nextval('public.calendar_events_id_seq'::regclass);


--
-- Name: chapters id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapters ALTER COLUMN id SET DEFAULT nextval('public.chapters_id_seq'::regclass);


--
-- Name: class_teacher id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.class_teacher ALTER COLUMN id SET DEFAULT nextval('public.class_teacher_id_seq'::regclass);


--
-- Name: classes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.classes ALTER COLUMN id SET DEFAULT nextval('public.classes_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: linkeds id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.linkeds ALTER COLUMN id SET DEFAULT nextval('public.linkeds_id_seq'::regclass);


--
-- Name: materi_pdfs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.materi_pdfs ALTER COLUMN id SET DEFAULT nextval('public.materi_pdfs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: package_classes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.package_classes ALTER COLUMN id SET DEFAULT nextval('public.package_classes_id_seq'::regclass);


--
-- Name: packages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.packages ALTER COLUMN id SET DEFAULT nextval('public.packages_id_seq'::regclass);


--
-- Name: payment_methods id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_methods ALTER COLUMN id SET DEFAULT nextval('public.payment_methods_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: profile_siswas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profile_siswas ALTER COLUMN id SET DEFAULT nextval('public.profile_siswas_id_seq'::regclass);


--
-- Name: questions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.questions ALTER COLUMN id SET DEFAULT nextval('public.questions_id_seq'::regclass);


--
-- Name: quiz_attempts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quiz_attempts ALTER COLUMN id SET DEFAULT nextval('public.quiz_attempts_id_seq'::regclass);


--
-- Name: quiz_results id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quiz_results ALTER COLUMN id SET DEFAULT nextval('public.quiz_results_id_seq'::regclass);


--
-- Name: quizzes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quizzes ALTER COLUMN id SET DEFAULT nextval('public.quizzes_id_seq'::regclass);


--
-- Name: soal_attempts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_attempts ALTER COLUMN id SET DEFAULT nextval('public.soal_attempts_id_seq'::regclass);


--
-- Name: soal_options id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_options ALTER COLUMN id SET DEFAULT nextval('public.soal_options_id_seq'::regclass);


--
-- Name: soal_questions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_questions ALTER COLUMN id SET DEFAULT nextval('public.soal_questions_id_seq'::regclass);


--
-- Name: soal_results id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_results ALTER COLUMN id SET DEFAULT nextval('public.soal_results_id_seq'::regclass);


--
-- Name: soal_sections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_sections ALTER COLUMN id SET DEFAULT nextval('public.soal_sections_id_seq'::regclass);


--
-- Name: soal_sets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_sets ALTER COLUMN id SET DEFAULT nextval('public.soal_sets_id_seq'::regclass);


--
-- Name: students id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students ALTER COLUMN id SET DEFAULT nextval('public.students_id_seq'::regclass);


--
-- Name: teachers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teachers ALTER COLUMN id SET DEFAULT nextval('public.teachers_id_seq'::regclass);


--
-- Name: transactions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions ALTER COLUMN id SET DEFAULT nextval('public.transactions_id_seq'::regclass);


--
-- Name: user_learning_progress id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_learning_progress ALTER COLUMN id SET DEFAULT nextval('public.user_learning_progress_id_seq'::regclass);


--
-- Name: user_packages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_packages ALTER COLUMN id SET DEFAULT nextval('public.user_packages_id_seq'::regclass);


--
-- Name: user_soal_progress id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_soal_progress ALTER COLUMN id SET DEFAULT nextval('public.user_soal_progress_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: videos id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.videos ALTER COLUMN id SET DEFAULT nextval('public.videos_id_seq'::regclass);


--
-- Data for Name: announcements; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.announcements (id, category, title, description, is_new, published_at, created_at, updated_at) FROM stdin;
2	Update	Update Terbaru !!	ini dia update terbaru untuk informasi..\n	t	2026-04-02 16:31:20	2026-04-02 16:31:20	2026-04-02 16:31:20
3	Informasi	Informasi Terbaru !!	ini dia informasi nya, jadi kita lakukan untuk melakukan yang bisa..	t	2026-04-02 16:32:04	2026-04-02 16:32:04	2026-04-02 16:32:04
4	Promo	Promo Terbaru !!	ini dia promo yang terbaru untuk melakukan ..	t	2026-04-02 16:32:22	2026-04-02 16:32:22	2026-04-02 16:32:22
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache (key, value, expiration) FROM stdin;
tunas-bimbel-cache-a75f3f172bfb296f2e10cbfc6dfc1883:timer	i:1794681722;	1794681722
tunas-bimbel-cache-a75f3f172bfb296f2e10cbfc6dfc1883	i:1;	1794681722
tunas-bimbel-cache-76cfc1d2909727870e2f53026b4a4f93:timer	i:1794681890;	1794681890
tunas-bimbel-cache-76cfc1d2909727870e2f53026b4a4f93	i:5;	1794681890
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: calendar_events; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.calendar_events (id, title, description, event_date, created_at, updated_at) FROM stdin;
3	Pengumuman Beasiswa SDMPKS	Pengumuman Beasiswa SDMPKS tahun 2026	2026-07-30	2026-08-30 16:40:34	2026-08-30 16:40:34
\.


--
-- Data for Name: chapters; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.chapters (id, class_id, title, description, created_at, updated_at) FROM stdin;
1	2	Psikologi I	ini tahap I	2026-03-13 19:33:15	2026-03-13 19:33:15
3	2	Psikologi Lanjutan	Tahap Akhir	2026-03-13 19:33:49	2026-03-13 19:33:49
4	1	Kecambah	ini awal	2026-03-13 19:34:03	2026-03-13 19:34:03
5	1	Bagian-bagian Kelapa Sawit	ini bagian-bagian kelapa sawit	2026-03-13 19:34:28	2026-03-13 19:34:28
\.


--
-- Data for Name: class_teacher; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.class_teacher (id, class_id, teacher_id, created_at, updated_at) FROM stdin;
1	2	2	2026-08-27 17:13:20	2026-08-27 17:13:20
2	1	3	2026-08-27 17:13:25	2026-08-27 17:13:25
3	1	4	2026-08-27 17:14:35	2026-08-27 17:14:35
4	2	4	2026-08-27 17:14:39	2026-08-27 17:14:39
\.


--
-- Data for Name: classes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.classes (id, name, description, created_at, updated_at) FROM stdin;
1	Pengetahuan Kelapa Sawit	ini adalah soal tentang Kelapa Sawit ya	2026-03-13 19:32:32	2026-03-13 19:32:32
2	Psikologi	ini adalah soal Psikologi ya kawan-kawan	2026-03-13 19:32:54	2026-03-13 19:32:54
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: linkeds; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.linkeds (id, name, url, created_at, updated_at) FROM stdin;
1	peta_seleksi	https://a	2026-03-27 20:12:58	2026-03-27 20:12:58
3	informasi_beasiswa	https://beasiswasdmsawit.id/	2026-03-28 14:35:10	2026-03-28 14:35:10
4	informasi_kampus	https://beasiswasdmsawit.id/httpsbeasiswasdmsawitidjalur-pendaftaran	2026-03-28 14:36:20	2026-03-28 14:36:20
5	bonus_premium	https://bonus.com	2026-04-02 17:06:59	2026-04-02 17:06:59
6	faq	https://bonus.com	2026-04-02 17:07:05	2026-04-02 17:07:05
7	laporan	https://lapormasalah.com	2026-04-02 17:08:19	2026-04-02 17:08:19
8	klaim_voucher	https://klaimvoucher.co.id	2026-04-02 17:08:54	2026-04-02 17:08:54
\.


--
-- Data for Name: materi_pdfs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.materi_pdfs (id, chapter_id, title, pdf_url, created_at, updated_at, storage_type, file_mime_type, file_size) FROM stdin;
1	4	Awal Kecambah	https://drive.google.com/file/d/1ImP_JKi5qyGnnKojeYlXQrk0qZl--EKT/view?usp=drive_link	2026-03-13 19:45:10	2026-03-13 19:45:10	external_url	\N	\N
2	5	Bagian Kelapa Sawit	https://drive.google.com/file/d/1ImP_JKi5qyGnnKojeYlXQrk0qZl--EKT/view?usp=drive_link	2026-03-13 19:45:25	2026-03-13 19:45:25	external_url	\N	\N
3	1	Psikologi Pertama	https://drive.google.com/file/d/1ImP_JKi5qyGnnKojeYlXQrk0qZl--EKT/view?usp=drive_link	2026-03-13 19:45:59	2026-03-13 19:45:59	external_url	\N	\N
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_03_03_154424_create_personal_access_tokens_table	1
5	2026_03_06_192933_create_class_rooms_table	1
6	2026_03_06_192934_create_chapters_table	1
7	2026_03_06_192935_create_videos_table	1
8	2026_03_06_192936_create_materi_pdfs_table	1
9	2026_03_06_192936_create_quizzes_table	1
10	2026_03_06_192937_create_questions_table	1
11	2026_03_06_192937_create_quiz_results_table	1
12	2026_03_06_192942_create_calendar_events_table	1
13	2026_03_06_204210_add_role_to_users_table	1
14	2026_03_07_054653_create_students_table	1
15	2026_03_07_054727_create_teachers_table	1
16	2026_03_09_144557_add_subtitle_to_videos_table	1
17	2026_03_13_201159_add_image_to_questions_table	2
20	2026_03_14_101001_create_user_learning_progress_table	3
21	2026_03_19_140207_add_answers_to_quiz_results	4
22	2026_03_21_171442_create_soal_sections_table	5
23	2026_03_21_171502_create_soal_sets_table	5
24	2026_03_21_171531_create_soal_questions_table	5
25	2026_03_21_171553_create_soal_options_table	5
26	2026_03_27_195902_create_linkeds_table	6
27	2026_03_28_182123_create_soal_results_table	7
29	2026_03_28_182208_create_user_soal_progress_table	8
30	2026_04_02_161135_create_announcements_table	9
32	2026_04_02_194647_create_profile_siswas_table	10
33	2026_04_11_185329_create_packages_table	11
34	2026_04_11_185541_create_package_classes_table	11
35	2026_04_11_192336_add_image_to_packages	12
36	2026_04_11_212452_create_user_packages_table	13
37	2026_04_11_215648_add_class_id_to_soal_sections	14
38	2026_08_25_173645_update_roles_in_users_table	15
39	2026_08_26_151745_create_class_teacher_table	16
40	2026_08_29_212448_create_payment_methods_table	17
41	2026_08_29_212458_create_transactions_table	17
42	2026_08_29_212507_add_access_fields_to_user_packages_table	17
43	2026_08_29_231752_add_private_storage_fields_to_materi_pdfs_table	18
44	2026_08_29_231759_add_private_storage_fields_to_videos_table	18
45	2026_08_29_231806_create_quiz_attempts_table	18
46	2026_08_29_231812_create_soal_attempts_table	18
47	2026_08_30_004224_repair_private_media_columns	19
48	2026_08_30_175225_repair_assessment_attempt_tables	20
49	2026_08_30_175737_repair_quiz_and_soal_attempt_tables	21
51	2026_09_20_145433_add_completed_items_to_user_learning_progress_table	22
52	2026_09_20_143448_add_unique_user_chapter_to_user_learning_progress_table	23
\.


--
-- Data for Name: package_classes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.package_classes (id, package_id, class_id, created_at, updated_at) FROM stdin;
6	5	1	\N	\N
7	6	2	\N	\N
8	7	1	\N	\N
9	7	2	\N	\N
10	8	1	\N	\N
11	9	2	\N	\N
\.


--
-- Data for Name: packages; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.packages (id, name, description, price, created_at, updated_at, image) FROM stdin;
6	PAKET B	ini adalah paket B	10000	2026-04-11 22:06:14	2026-04-11 22:06:14	packages/sNE5c6DvU89zvmuKVNmxCSs4IRkBBhn0Gld9q48A.jpg
7	Paket Full	ini paket FULL	50000	2026-04-11 22:16:59	2026-04-11 22:16:59	\N
8	PAKET C - Khusus Pemula	ini khusus untuk pemula ya...\ncoba kamu rasakan bagaimana sensasi nya, coba dulu saja dulu ya	125000	2026-04-19 08:51:27	2026-04-19 08:51:27	packages/wiZJHkCJuFl6f8jKdEE7qgQ8a20ebegI5zS9IYIa.png
9	PAKET D - COBA SAJA DULU YA..	Ini saja dulu coba dulu ya, kita coba nanti saja yang bagus	50000	2026-04-19 08:52:32	2026-04-19 08:52:32	packages/GMAaA17iX9GIUscvkkFwkWHJAini9E6aHxPyWj6b.jpg
5	PAKET AA	ini paket BB	25000	2026-04-11 20:40:11	2026-04-19 08:53:09	packages/8JArmYTvcarkqhq65XB4IMyX8TZlxyv6AfyOzoh7.png
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: payment_methods; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.payment_methods (id, name, type, provider, account_name, account_number, qr_image, instructions, mode, gateway_provider, requires_proof, is_active, sort_order, created_at, updated_at) FROM stdin;
2	BANK BRI	bank_transfer	BRI	YOGI SEPDU DEHIYA	560301022977535	\N	TF sesuai Urutan	manual	\N	t	t	2	2026-08-29 22:00:17	2026-08-29 22:00:17
1	DANA	ewallet	DANA	YOGI SEPDU DEHIYA	082252992668	\N	TF Sesuai Nominal	manual	\N	t	t	1	2026-08-29 21:58:05	2026-08-29 22:44:50
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
1	App\\Models\\User	2	mobile-token	08940ba9921b00f3860f7230a8c7ebd648c472112e3ec3814956cfcfc78f5efb	["*"]	\N	\N	2026-03-14 10:33:18	2026-03-14 10:33:18
10	App\\Models\\User	2	mobile-token	bedde21ba45c908ee6761ea6f4b0d9ee36ee83a584ab208637cc40b4737b953b	["student"]	2026-03-19 14:12:12	\N	2026-03-19 10:06:23	2026-03-19 14:12:12
8	App\\Models\\User	2	mobile-token	0a7848dfa50aba79c8a263f47ad20651b3670010d991a0252ae617e5d250c5eb	["student"]	2026-03-15 17:19:37	\N	2026-03-15 16:44:57	2026-03-15 17:19:37
9	App\\Models\\User	2	mobile-token	26ff272bbb07f86fc0f8bbb43c50c422b5e72fe062e8e18db507ed4be4c7c7bd	["student"]	\N	\N	2026-03-18 18:47:32	2026-03-18 18:47:32
18	App\\Models\\User	2	mobile-token	1803f0f16c564b6af3906e7792386faaf4a4b451a463738ad827328c78040a24	["student"]	2026-04-02 16:35:30	\N	2026-04-02 16:33:12	2026-04-02 16:35:30
40	App\\Models\\User	17	mobile-token	56c37f86bbdfc4e6d641bb229f0852fb9eaa70eecfa7de832d6cbb5059b4f319	["student"]	2026-11-14 18:43:53	\N	2026-11-14 18:41:03	2026-11-14 18:43:53
30	App\\Models\\User	2	mobile-token	e1f3334b2809154ce362b53cfaf36748631d034c280078dfee4b8bbb3e9fc8fa	["student"]	2026-04-11 13:03:49	\N	2026-04-11 13:01:01	2026-04-11 13:03:49
7	App\\Models\\User	2	mobile-token	929612b00b7f1b0dd5844ee1ae484d7942223ea7a937441979f4b93a7456cbf3	["student"]	2026-03-15 09:45:38	\N	2026-03-15 07:01:24	2026-03-15 09:45:38
21	App\\Models\\User	2	mobile-token	8a82bd374c55ba6b9b3718f91da1b345953d647f4078318382dcf0dd02f91ff1	["student"]	2026-04-02 19:56:07	\N	2026-04-02 19:42:42	2026-04-02 19:56:07
31	App\\Models\\User	2	mobile-token	517c617c1ea3c8b944684ab09bc047c7ddf867ac1c1453a036c09f04658747e4	["student"]	2026-04-11 22:26:18	\N	2026-04-11 21:37:02	2026-04-11 22:26:18
5	App\\Models\\User	2	mobile-token	06bb23efc978cdd61c2bb27933176ba64b27aad0a8fdb696b9983dbff8025b4b	["student"]	2026-03-15 06:39:19	\N	2026-03-14 16:42:03	2026-03-15 06:39:19
24	App\\Models\\User	4	auth_token	defd698e12915db303bde7dc43ddac2266d06fab2e4bff1b8df2a1d6cde49a29	["*"]	\N	\N	2026-04-04 19:50:31	2026-04-04 19:50:31
4	App\\Models\\User	2	mobile-token	ed37880c3a037e9e547c6810550b186bb0825d7d2ac9a23be425807927e0125e	["student"]	\N	\N	2026-03-14 16:37:44	2026-03-14 16:37:44
22	App\\Models\\User	2	mobile-token	b41ba3c1ebb8e2454c8918be08a0bd800b5c08beadec92319d0946d472cda8d8	["student"]	2026-04-03 16:58:46	\N	2026-04-03 16:53:20	2026-04-03 16:58:46
15	App\\Models\\User	2	mobile-token	bd0b3a78fe08bb9ab9f03fd334042b783279a31dd7030e520ebe821f8ee37cc4	["student"]	2026-03-28 20:42:14	\N	2026-03-28 16:20:49	2026-03-28 20:42:14
16	App\\Models\\User	2	mobile-token	9fa8389a4d70b0dbe0daf4bbb7704295937c4e764180602b30aafb99b9af5901	["student"]	\N	\N	2026-04-02 16:07:19	2026-04-02 16:07:19
14	App\\Models\\User	2	mobile-token	9ce794cd5d72ecdc2ae7f60baac2af77f18c5cd053323696cf5daee6c1f161c3	["student"]	2026-03-28 15:30:58	\N	2026-03-28 15:30:40	2026-03-28 15:30:58
6	App\\Models\\User	2	mobile-token	061579a8d212884b2b6331377ba5262aeefdf25115e7cb16ae7a9c1e358f2b19	["student"]	2026-03-15 10:02:19	\N	2026-03-15 06:57:57	2026-03-15 10:02:19
3	App\\Models\\User	2	mobile-token	79c35a00fd0ae18795d389f2c8ea1299a1ab2c02b4ba1c03d762cda65c3ff6c4	["student"]	2026-03-14 18:57:32	\N	2026-03-14 11:09:35	2026-03-14 18:57:32
13	App\\Models\\User	2	mobile-token	50f32264d6d54aca781e14993918de2f6e178c45f31de95bb3dbb125c3d869e7	["student"]	2026-03-28 16:18:08	\N	2026-03-28 10:18:49	2026-03-28 16:18:08
11	App\\Models\\User	2	mobile-token	88a761d74d4c4ff2d5f3a72e2010b66655647386250154b18fd4b9a3c6c6a60d	["student"]	2026-03-20 04:46:07	\N	2026-03-20 04:11:34	2026-03-20 04:46:07
12	App\\Models\\User	2	mobile-token	6909080d5c8822b22fc91dc32bb41dde46ab14bfc849b20d84f004cb10784213	["student"]	2026-03-21 17:13:09	\N	2026-03-21 17:12:02	2026-03-21 17:13:09
27	App\\Models\\User	2	mobile-token	94c44412f4cbe2046ce9445f0f34b18526902440302a0337f279ccf4dde94376	["student"]	2026-04-10 18:07:36	\N	2026-04-10 17:43:58	2026-04-10 18:07:36
25	App\\Models\\User	8	mobile-token	058907a24e573fa92cacd727e1eda5335a5561e145cbdf67a465ca291ee212b0	["student"]	2026-04-04 20:17:14	\N	2026-04-04 20:16:28	2026-04-04 20:17:14
28	App\\Models\\User	2	mobile-token	4bdce6ade4326e43a68d0c01672dfe22a5048b7429c634d3558fa37c5260bbb6	["student"]	2026-04-11 12:54:41	\N	2026-04-11 12:53:39	2026-04-11 12:54:41
34	App\\Models\\User	17	mobile-token	3c043a578971dd1dd66a163d31245507b61e9b86591bcadde3cfe9e31e699ecb	["student"]	2026-08-30 18:02:07	\N	2026-08-30 17:44:26	2026-08-30 18:02:07
32	App\\Models\\User	2	mobile-token	d1ee28217d59ef9d904402403afd375475f1373a0450b857f99ecfde96397b8d	["student"]	2026-04-11 21:41:07	\N	2026-04-11 21:40:06	2026-04-11 21:41:07
\.


--
-- Data for Name: profile_siswas; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.profile_siswas (id, user_id, name, phone, gender, province_id, regency_id, district_id, village_id, postal_code, avatar, created_at, updated_at) FROM stdin;
1	2	Yogi Sepdu Dehiya	085522336688	pria	14	1407	1407020	1407020012	\N	\N	2026-04-02 19:55:12	2026-04-02 19:55:42
2	17	Yogi Sepdu	\N	\N	\N	\N	\N	\N	\N	\N	2026-08-25 17:10:14	2026-08-25 17:10:14
\.


--
-- Data for Name: questions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.questions (id, quiz_id, question, option_a, option_b, option_c, option_d, correct_answer, created_at, updated_at, image) FROM stdin;
14	5	ini soal sawit...	d	b	c	d	D	2026-03-15 08:27:25	2026-03-15 08:27:25	questions/pengetahuan-sawit/TRV94BEr90eB1ySzDEvHeP0T0XYV333bJu93356A.jpg
15	5	ini soal yang gagal muncul	A	D	B	A	A	2026-03-15 09:45:30	2026-03-15 09:45:30	questions/pengetahuan-sawit/9d107d58-e0e0-4cca-ab8e-1dd97953d0f7.jpg
16	5	ini soal	A	A	a	s	A	2026-03-15 09:48:41	2026-03-15 09:48:41	questions/pengetahuan-sawit/c6f7de01-fcc1-4622-a5b0-05f8c2b370eb.jpg
17	5	ini lagi pertanyaan nya ada ...	B	A	C	B	B	2026-03-15 09:51:33	2026-03-15 09:51:33	questions/pengetahuan-sawit/d45e919f-174c-4bc9-bc1a-e5b2af88590a.jpg
18	6	ini psikologi	A	B	A	D	C	2026-03-15 10:00:00	2026-03-15 10:00:00	questions/psikologi-i/9324bbae-f67a-4f98-96f3-e13003279a9d.jpg
\.


--
-- Data for Name: quiz_attempts; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.quiz_attempts (id, created_at, updated_at, token, user_id, quiz_id, status, started_at, expires_at, submitted_at) FROM stdin;
1	2026-08-30 17:55:43	2026-08-30 18:00:53	b09ee39d-560f-4cee-a8e8-d7ed0f477f93	17	5	submitted	2026-08-30 17:55:43	2026-08-30 18:55:43	2026-08-30 18:00:53
2	2026-09-20 14:13:18	2026-09-20 14:13:22	d1ba8e35-9083-4b14-b1ff-371917306ff1	2	6	submitted	2026-09-20 14:13:18	2026-09-20 14:43:18	2026-09-20 14:13:22
3	2026-09-20 14:14:13	2026-09-20 14:14:16	c28cb9b8-a07f-4cf5-a63c-79df14e2a9a2	2	6	submitted	2026-09-20 14:14:13	2026-09-20 14:44:13	2026-09-20 14:14:16
4	2026-09-20 14:15:14	2026-09-20 14:15:22	67a71f8c-3f30-4ee8-bd3f-c3f1892f27c6	2	5	submitted	2026-09-20 14:15:14	2026-09-20 15:15:14	2026-09-20 14:15:22
5	2026-09-20 15:33:54	2026-09-20 15:33:57	cbafd580-a148-44a9-907f-6ff5b1467c7f	2	6	submitted	2026-09-20 15:33:54	2026-09-20 16:03:54	2026-09-20 15:33:57
6	2026-09-20 15:42:12	2026-09-20 15:42:18	8fa6c5b6-c8af-4293-91ca-caf48d2f182c	2	6	submitted	2026-09-20 15:42:12	2026-09-20 16:12:12	2026-09-20 15:42:18
7	2026-09-20 15:53:10	2026-09-20 15:53:26	1b134f66-4904-4ba0-bc59-a71d084ebdfd	2	5	submitted	2026-09-20 15:53:10	2026-09-20 16:53:10	2026-09-20 15:53:26
\.


--
-- Data for Name: quiz_results; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.quiz_results (id, user_id, quiz_id, score, correct, wrong, empty, created_at, updated_at, answers) FROM stdin;
14	2	5	0	0	4	0	2026-04-10 18:07:00	2026-04-10 18:07:00	[{"questionId":14,"selectedAnswer":"B"},{"questionId":15,"selectedAnswer":"C"},{"questionId":16,"selectedAnswer":"B"},{"questionId":17,"selectedAnswer":"A"}]
15	2	5	25	1	3	0	2026-04-10 18:07:26	2026-04-10 18:07:26	[{"questionId":14,"selectedAnswer":"A"},{"questionId":15,"selectedAnswer":"B"},{"questionId":16,"selectedAnswer":"A"},{"questionId":17,"selectedAnswer":"C"}]
16	17	5	25	1	3	0	2026-08-30 18:00:53	2026-08-30 18:00:53	{"14":"B","15":"C","16":"B","17":"B"}
17	2	6	0	0	0	1	2026-09-20 14:13:22	2026-09-20 14:13:22	{"18":""}
18	2	6	0	0	0	1	2026-09-20 14:14:16	2026-09-20 14:14:16	{"18":""}
19	2	5	25	1	3	0	2026-09-20 14:15:22	2026-09-20 14:15:22	{"14":"D","15":"C","16":"B","17":"C"}
20	2	6	0	0	1	0	2026-09-20 15:33:57	2026-09-20 15:33:57	{"18":"B"}
21	2	6	0	0	1	0	2026-09-20 15:42:18	2026-09-20 15:42:18	{"18":"B"}
22	2	5	75	3	1	0	2026-09-20 15:53:26	2026-09-20 15:53:26	{"14":"D","15":"C","16":"A","17":"B"}
\.


--
-- Data for Name: quizzes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.quizzes (id, class_id, title, duration, created_at, updated_at) FROM stdin;
6	2	Psikologi I	30	2026-03-13 21:58:33	2026-03-13 21:58:33
5	1	Pengetahuan Sawit	60	2026-03-13 21:55:37	2026-03-13 21:55:37
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
VZbU8fsifNTJuqXHMYLu0I3Br6GSE2MOV7jYfhl6	1	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0	YTo0OntzOjY6Il90b2tlbiI7czo0MDoibXdua1NIbzhucENEMERCenZpYlFnSjlHRWs3b2NITVpuQ29Lb2x2SCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdHVkZW50IjtzOjU6InJvdXRlIjtzOjEzOiJzdHVkZW50LmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9	1794681384
PEZjurPztBc5RIkdJ6twGJ4jJjtbhIta2huqRZlA	\N	127.0.0.1	Mozilla/5.0 (Linux; Android 13; sdk_gphone64_x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Mobile Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoielE4YkpYVzZrYndqQ0Q5aGgxU05BcHF6SjlYcGZUY3g4TkJkMnlTSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMC4wLjIuMjo4MDAwL2NoZWNrb3V0LzkiO3M6NToicm91dGUiO3M6ODoiY2hlY2tvdXQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1794681443
61hkvfZJ0wceOSmJOxgrL01Cod7BVrQqoHJujOkY	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiSkJsQmZMMUgzc1llOWNLaW8zT3U1SHZmTWhHOUNKRmpnUFJIdXVuNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==	1794678851
\.


--
-- Data for Name: soal_attempts; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.soal_attempts (id, created_at, updated_at, token, user_id, soal_set_id, status, started_at, expires_at, submitted_at) FROM stdin;
\.


--
-- Data for Name: soal_options; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.soal_options (id, soal_question_id, key, text, created_at, updated_at) FROM stdin;
37	10	A	A	2026-04-11 22:02:55	2026-04-11 22:02:55
38	10	B	B	2026-04-11 22:02:55	2026-04-11 22:02:55
39	10	C	C	2026-04-11 22:02:55	2026-04-11 22:02:55
40	10	D	D	2026-04-11 22:02:55	2026-04-11 22:02:55
41	11	A	B	2026-04-11 22:03:10	2026-04-11 22:03:10
42	11	B	C	2026-04-11 22:03:10	2026-04-11 22:03:10
43	11	C	D	2026-04-11 22:03:10	2026-04-11 22:03:10
44	11	D	E	2026-04-11 22:03:10	2026-04-11 22:03:10
45	12	A	B	2026-04-11 22:04:15	2026-04-11 22:04:15
46	12	B	C	2026-04-11 22:04:15	2026-04-11 22:04:15
47	12	C	B	2026-04-11 22:04:15	2026-04-11 22:04:15
48	12	D	B	2026-04-11 22:04:15	2026-04-11 22:04:15
49	13	A	b	2026-04-11 22:05:06	2026-04-11 22:05:06
50	13	B	a	2026-04-11 22:05:06	2026-04-11 22:05:06
51	13	C	c	2026-04-11 22:05:06	2026-04-11 22:05:06
52	13	D	d	2026-04-11 22:05:06	2026-04-11 22:05:06
\.


--
-- Data for Name: soal_questions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.soal_questions (id, soal_set_id, question, correct_answer, created_at, updated_at) FROM stdin;
10	5	ini adlaah apa?	A	2026-04-11 22:02:55	2026-04-11 22:02:55
11	5	ini juga apa?	A	2026-04-11 22:03:10	2026-04-11 22:03:10
12	6	ini contoh nya apa?	B	2026-04-11 22:04:15	2026-04-11 22:04:15
13	7	jika A, maka = ...	A	2026-04-11 22:05:06	2026-04-11 22:05:06
\.


--
-- Data for Name: soal_results; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.soal_results (id, user_id, soal_set_id, score, correct, wrong, empty, answers, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: soal_sections; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.soal_sections (id, title, created_at, updated_at, class_id) FROM stdin;
4	Kelas A	2026-04-11 22:01:47	2026-04-11 22:01:47	1
5	Kelas A 2	2026-04-11 22:01:58	2026-04-11 22:01:58	1
6	Kelas B	2026-04-11 22:02:02	2026-04-11 22:02:02	2
\.


--
-- Data for Name: soal_sets; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.soal_sets (id, soal_section_id, title, total_questions, duration, points, badge, created_at, updated_at) FROM stdin;
5	4	Pengetahuan Dasar-dasar Sawit	2	30	15	hots	2026-04-11 22:02:31	2026-04-11 22:03:10
6	5	Pengetahuan Sawit II	1	30	85	hard	2026-04-11 22:03:48	2026-04-11 22:04:15
7	6	Logika Verbal	1	30	15	medium	2026-04-11 22:04:45	2026-04-11 22:05:06
\.


--
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.students (id, user_id, phone, school, grade, address, birth_date, created_at, updated_at) FROM stdin;
1	2	082252992668	POLKAM	11	Riau	2004-03-14	2026-03-13 19:28:53	2026-03-13 19:28:53
3	18	081268450490	SMA 1 Pekanbaru	11	Jl Bengkalis Indah. Balai Raja, Kec. Pinggir, Kab. Bengkalis, Riau	2026-08-26	2026-08-25 17:15:11	2026-08-25 17:15:11
2	17	081268450490	SMA 1 Pekanbaru	11	Jl Bengkalis Indah. Balai Raja, Kec. Pinggir, Kab. Bengkalis, Riau	2026-08-26	2026-08-25 17:10:14	2026-08-25 17:23:05
\.


--
-- Data for Name: teachers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.teachers (id, user_id, phone, company, specialization, experience_years, bio, created_at, updated_at) FROM stdin;
2	19	085225645789	POLKAM	Fisika	3	santai	2026-08-26 16:13:12	2026-08-26 16:13:12
3	6	081234567894	\N	Mathematics	2	Matematika	2026-08-26 16:45:48	2026-08-26 16:45:48
4	4	081268450490	UNJA	Psikologi	2	Ilmu Psikologi	2026-08-27 17:14:17	2026-08-27 17:14:17
\.


--
-- Data for Name: transactions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.transactions (id, invoice_no, public_token, user_id, package_id, payment_method_id, package_name, package_base_price, billing, duration_months, subtotal, discount, total, customer_name, customer_email, customer_phone, payment_method_name, payment_provider, payment_account_name, payment_account_number, payment_mode, gateway_provider, gateway_reference, gateway_payload, status, proof_path, proof_original_name, proof_mime_type, proof_uploaded_at, reviewed_by, reviewed_at, rejection_reason, paid_at, expires_at, created_at, updated_at) FROM stdin;
1	INV-TB-20260829-RNGFTP	f2e4dc21-6800-4ad7-8b7f-1af0803a8307	17	7	1	Paket Full	50000.00	monthly	1	50000.00	0.00	50000.00	Yogi Sepdu Dehiya	bprskcub@gmail.com	082252664455	BRI	DANA	YOGI SEPDU DEHIYA	082252992668	manual	\N	\N	\N	paid	payment-proofs/2026/08/inv-tb-20260829-rngftp-3nibz0lmtzcc4y0t.jpeg	WhatsApp Image 2026-08-25 at 16.39.02.jpeg		2026-08-29 22:44:00	1	2026-08-29 22:58:23	\N	2026-08-29 22:58:23	2026-08-30 22:35:54	2026-08-29 22:35:54	2026-08-29 22:58:23
2	INV-TB-20260925-0BNL86	a644ee3a-906c-4d8c-9fd4-d64c29553526	16	6	1	PAKET B	10000.00	monthly	1	10000.00	0.00	10000.00	YOGI SEPDU	productiondusun@gmail.com	082252992668	DANA	DANA	YOGI SEPDU DEHIYA	082252992668	manual	\N	\N	\N	waiting_verification	payment-proofs/2026/09/inv-tb-20260925-0bnl86-zcgduehsw7dqylxt.jpeg	WhatsApp Image 2026-09-13 at 02.33.16.jpeg		2026-09-25 17:41:43	\N	\N	\N	\N	2026-09-26 17:40:03	2026-09-25 17:40:03	2026-09-25 17:41:43
3	INV-TB-20260925-SA3KLW	2b1c396d-4d63-4755-92a4-bc074b113c07	17	9	2	PAKET D - COBA SAJA DULU YA..	50000.00	yearly	12	600000.00	100000.00	500000.00	YOGI SEPDU	bprskcub@gmail.com	082252992668	BANK BRI	BRI	YOGI SEPDU DEHIYA	560301022977535	manual	\N	\N	\N	waiting_verification	payment-proofs/2026/09/inv-tb-20260925-sa3klw-eklrr3rzivugkllp.jpeg	WhatsApp Image 2026-09-13 at 02.33.16.jpeg	image/jpeg	2026-09-25 18:16:51	\N	\N	\N	\N	2026-09-26 18:16:32	2026-09-25 18:16:32	2026-09-25 18:16:51
\.


--
-- Data for Name: user_learning_progress; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.user_learning_progress (id, user_id, chapter_id, video_id, pdf_id, quiz_id, status, progress_percent, created_at, updated_at, completed_video_ids, completed_pdf_ids, completed_quiz_ids) FROM stdin;
64	2	5	\N	\N	\N	t	100	2026-09-20 15:53:03	2026-09-20 15:53:27	[2]	[2]	[5]
63	2	1	\N	\N	\N	t	100	2026-09-20 15:41:58	2026-09-20 15:42:19	[3, 4, 8]	[3]	[6]
\.


--
-- Data for Name: user_packages; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.user_packages (id, user_id, package_id, created_at, updated_at, transaction_id, status, activated_at, expires_at) FROM stdin;
2	2	6	2026-04-12 04:41:04	2026-04-12 04:41:04	\N	active	\N	\N
3	2	5	2026-04-12 05:07:15	2026-04-12 05:07:15	\N	active	\N	\N
4	17	7	2026-08-29 22:58:23	2026-08-29 22:58:23	1	active	2026-08-29 22:58:23	2026-09-29 22:58:23
\.


--
-- Data for Name: user_soal_progress; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.user_soal_progress (id, user_id, soal_set_id, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role) FROM stdin;
17	Yogi Sepdu	bprskcub@gmail.com	2026-08-25 17:14:10	$2y$12$olpLt5F2CgchqchhYB5d2uoQixUa1iBX5FvtL5ACW.sxbtaxd8BEK	\N	2026-08-25 17:10:14	2026-08-25 17:14:10	student
18	Anishah Kartari	anishahkartari@gmail.com	\N	$2y$12$K/5RJklGQH/6clfVzjMqeOfA5CcVNmsQGsRj0F0ojXXSOIU9SfWsW	\N	2026-08-25 17:15:11	2026-08-25 17:15:11	student
6	retno yogi	reyang@gmail.com	\N	$2y$12$ku/b8/8s5xjSyFOoe9LwMONL1ca7yuKAKq4xzS/RFzN.P4O4ssJhG	\N	2026-04-04 20:09:21	2026-08-26 16:45:48	teacher
8	Retno Anggraini	retno@gmail.com	\N	$2y$12$R4JaS1nCQ2oANoG4telZqOKo0VmznX0XvdaqP/2brMjD9R1WGiFsW	\N	2026-04-04 20:16:08	2026-04-04 20:16:08	student
20	Yogi Sepdu	yogi@gmail.com	2026-08-27 17:12:09	$2y$12$8RDe28rahMNmWlyGp22Tyu1fLC9MBa2J63sl0JBKkppbmFCNCIMCm	\N	2026-08-27 17:12:09	2026-08-27 17:12:09	admin
4	Yogi	yogi@test.com	\N	$2y$12$rzHS0jiCaYOV6EglAnfwl.ibmCw8Pp3kl8YfIPwrqEbRLtprhmA0S	\N	2026-04-04 19:50:31	2026-08-27 17:14:17	teacher
2	Yogi Sepdu Dehiya	yogisepdu@gmail.com	2026-04-05 11:18:44	$2y$12$oLUUnl9k.ZkM5x6oNRGQ1eKfcqHImmu9ald7pUTOIbcKJBazLuyjW	u575Ch8jH0T9a6NczAPDrMOPCu2pMbGPJ8ULSuhQPmfKYh0cMGDK97nyHcJC	2026-03-13 19:28:53	2026-09-20 14:12:16	student
19	retno yogi	yogireyang@gmail.com	2026-08-26 16:13:12	$2y$12$k5qM2.sa3OXlJcxbBSwjb.7WkPFHlh6c1SFNkU3ehvz9ZwEVAJ9mW	\N	2026-08-26 16:13:12	2026-11-14 18:22:13	teacher
1	Administrator	admin@gmail.com	2026-03-13 19:27:35	$2y$12$HRj5r/Sx.5FMIEu71ZYL7usluH.SU8eJ6GL42CS7Y/2ZhNVALCEAu	E15WPJwT4Ykn6Wlof7omarZVXiZFEy36FxLoheWKTFgiHiO94Y1bndcbxIQ9	2026-03-13 19:27:36	2026-03-13 19:27:36	administrator
16	yogi succes	productiondusun@gmail.com	2026-04-05 11:14:29	$2y$12$bpB9nU5JuTxZJ3z.zre/weQvj0gfxo2CjbUj/YY438/9iqS5Lzj2C	4GAAQdJ6TA6jmcwW5neyqIXMAFAjX3CsJrXfhNrTCitygl8JdXV7Y9kif5SJ	2026-04-05 11:14:08	2026-04-05 18:19:51	student
\.


--
-- Data for Name: videos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.videos (id, chapter_id, title, youtube_id, created_at, updated_at, subtitle, source_type, video_path, video_mime_type, video_size) FROM stdin;
1	4	ini pengenalan kecambah	XwjHLJ5mWM4&list=RDqQzdAsjWGPg&index=10	2026-03-13 19:35:16	2026-03-13 19:35:16	ini adalah pengenalan kecambah kelapa sawit	youtube	\N	\N	\N
2	5	Pengenalan Bagian-bagian Kelapa Sawit	XwjHLJ5mWM4&list=RDqQzdAsjWGPg&index=10	2026-03-13 19:35:55	2026-03-13 19:35:55	ini adalah bagian-bagian Kelapa Sawit	youtube	\N	\N	\N
3	1	Psikologi I	XwjHLJ5mWM4&list=RDqQzdAsjWGPg&index=10	2026-03-13 19:37:18	2026-03-13 19:37:18	ini tahap I	youtube	\N	\N	\N
4	1	ini Psikologi I - Part 2	ini Psikologi I - Part 2	2026-03-13 19:37:45	2026-03-13 19:37:45	ini Tahap Psikologi I - Part 2	youtube	\N	\N	\N
6	3	Tahap Lanjutan	v=w46bWxS9IjY&list=RDqQzdAsjWGPg&index=11	2026-03-13 19:43:36	2026-03-13 19:43:36	ini tahap lanjutan ya	youtube	\N	\N	\N
8	1	Psikologi Video		2026-08-30 01:01:08	2026-08-30 01:01:08	Psikologi Video	private_file	learning/videos/1/CvuIsFGH238HFIKAkd0UMDBqBjbw16rAch8nbfQv.mp4	video/mp4	1605268
\.


--
-- Name: announcements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.announcements_id_seq', 4, true);


--
-- Name: calendar_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.calendar_events_id_seq', 35, true);


--
-- Name: chapters_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.chapters_id_seq', 5, true);


--
-- Name: class_teacher_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.class_teacher_id_seq', 4, true);


--
-- Name: classes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.classes_id_seq', 3, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: linkeds_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.linkeds_id_seq', 8, true);


--
-- Name: materi_pdfs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.materi_pdfs_id_seq', 5, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 51, true);


--
-- Name: package_classes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.package_classes_id_seq', 11, true);


--
-- Name: packages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.packages_id_seq', 9, true);


--
-- Name: payment_methods_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.payment_methods_id_seq', 2, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 40, true);


--
-- Name: profile_siswas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.profile_siswas_id_seq', 2, true);


--
-- Name: questions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.questions_id_seq', 18, true);


--
-- Name: quiz_attempts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.quiz_attempts_id_seq', 7, true);


--
-- Name: quiz_results_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.quiz_results_id_seq', 22, true);


--
-- Name: quizzes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.quizzes_id_seq', 6, true);


--
-- Name: soal_attempts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.soal_attempts_id_seq', 1, false);


--
-- Name: soal_options_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.soal_options_id_seq', 52, true);


--
-- Name: soal_questions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.soal_questions_id_seq', 13, true);


--
-- Name: soal_results_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.soal_results_id_seq', 6, true);


--
-- Name: soal_sections_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.soal_sections_id_seq', 6, true);


--
-- Name: soal_sets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.soal_sets_id_seq', 7, true);


--
-- Name: students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.students_id_seq', 3, true);


--
-- Name: teachers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.teachers_id_seq', 4, true);


--
-- Name: transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.transactions_id_seq', 3, true);


--
-- Name: user_learning_progress_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.user_learning_progress_id_seq', 64, true);


--
-- Name: user_packages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.user_packages_id_seq', 4, true);


--
-- Name: user_soal_progress_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.user_soal_progress_id_seq', 6, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 20, true);


--
-- Name: videos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.videos_id_seq', 8, true);


--
-- Name: announcements announcements_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.announcements
    ADD CONSTRAINT announcements_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: calendar_events calendar_events_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.calendar_events
    ADD CONSTRAINT calendar_events_pkey PRIMARY KEY (id);


--
-- Name: chapters chapters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapters
    ADD CONSTRAINT chapters_pkey PRIMARY KEY (id);


--
-- Name: class_teacher class_teacher_class_id_teacher_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.class_teacher
    ADD CONSTRAINT class_teacher_class_id_teacher_id_unique UNIQUE (class_id, teacher_id);


--
-- Name: class_teacher class_teacher_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.class_teacher
    ADD CONSTRAINT class_teacher_pkey PRIMARY KEY (id);


--
-- Name: classes classes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.classes
    ADD CONSTRAINT classes_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: linkeds linkeds_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.linkeds
    ADD CONSTRAINT linkeds_pkey PRIMARY KEY (id);


--
-- Name: materi_pdfs materi_pdfs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.materi_pdfs
    ADD CONSTRAINT materi_pdfs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: package_classes package_classes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.package_classes
    ADD CONSTRAINT package_classes_pkey PRIMARY KEY (id);


--
-- Name: packages packages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.packages
    ADD CONSTRAINT packages_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: payment_methods payment_methods_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_methods
    ADD CONSTRAINT payment_methods_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: profile_siswas profile_siswas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profile_siswas
    ADD CONSTRAINT profile_siswas_pkey PRIMARY KEY (id);


--
-- Name: questions questions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT questions_pkey PRIMARY KEY (id);


--
-- Name: quiz_attempts quiz_attempts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quiz_attempts
    ADD CONSTRAINT quiz_attempts_pkey PRIMARY KEY (id);


--
-- Name: quiz_attempts quiz_attempts_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quiz_attempts
    ADD CONSTRAINT quiz_attempts_token_unique UNIQUE (token);


--
-- Name: quiz_results quiz_results_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quiz_results
    ADD CONSTRAINT quiz_results_pkey PRIMARY KEY (id);


--
-- Name: quizzes quizzes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quizzes
    ADD CONSTRAINT quizzes_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: soal_attempts soal_attempts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_attempts
    ADD CONSTRAINT soal_attempts_pkey PRIMARY KEY (id);


--
-- Name: soal_attempts soal_attempts_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_attempts
    ADD CONSTRAINT soal_attempts_token_unique UNIQUE (token);


--
-- Name: soal_options soal_options_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_options
    ADD CONSTRAINT soal_options_pkey PRIMARY KEY (id);


--
-- Name: soal_questions soal_questions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_questions
    ADD CONSTRAINT soal_questions_pkey PRIMARY KEY (id);


--
-- Name: soal_results soal_results_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_results
    ADD CONSTRAINT soal_results_pkey PRIMARY KEY (id);


--
-- Name: soal_sections soal_sections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_sections
    ADD CONSTRAINT soal_sections_pkey PRIMARY KEY (id);


--
-- Name: soal_sets soal_sets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_sets
    ADD CONSTRAINT soal_sets_pkey PRIMARY KEY (id);


--
-- Name: students students_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- Name: teachers teachers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_pkey PRIMARY KEY (id);


--
-- Name: transactions transactions_invoice_no_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_invoice_no_unique UNIQUE (invoice_no);


--
-- Name: transactions transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_pkey PRIMARY KEY (id);


--
-- Name: transactions transactions_public_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_public_token_unique UNIQUE (public_token);


--
-- Name: user_learning_progress user_learning_progress_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_learning_progress
    ADD CONSTRAINT user_learning_progress_pkey PRIMARY KEY (id);


--
-- Name: user_learning_progress user_learning_progress_user_chapter_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_learning_progress
    ADD CONSTRAINT user_learning_progress_user_chapter_unique UNIQUE (user_id, chapter_id);


--
-- Name: user_packages user_packages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_packages
    ADD CONSTRAINT user_packages_pkey PRIMARY KEY (id);


--
-- Name: user_packages user_packages_user_id_package_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_packages
    ADD CONSTRAINT user_packages_user_id_package_id_unique UNIQUE (user_id, package_id);


--
-- Name: user_soal_progress user_soal_progress_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_soal_progress
    ADD CONSTRAINT user_soal_progress_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: videos videos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.videos
    ADD CONSTRAINT videos_pkey PRIMARY KEY (id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: payment_methods_is_active_sort_order_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_methods_is_active_sort_order_index ON public.payment_methods USING btree (is_active, sort_order);


--
-- Name: payment_methods_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_methods_type_index ON public.payment_methods USING btree (type);


--
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_expires_at_index ON public.personal_access_tokens USING btree (expires_at);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: transactions_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_created_at_index ON public.transactions USING btree (created_at);


--
-- Name: transactions_package_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_package_id_status_index ON public.transactions USING btree (package_id, status);


--
-- Name: transactions_payment_method_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_payment_method_id_status_index ON public.transactions USING btree (payment_method_id, status);


--
-- Name: transactions_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_status_index ON public.transactions USING btree (status);


--
-- Name: transactions_user_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_user_id_status_index ON public.transactions USING btree (user_id, status);


--
-- Name: user_packages_status_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_packages_status_expires_at_index ON public.user_packages USING btree (status, expires_at);


--
-- Name: user_packages_user_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_packages_user_id_status_index ON public.user_packages USING btree (user_id, status);


--
-- Name: chapters chapters_class_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapters
    ADD CONSTRAINT chapters_class_id_foreign FOREIGN KEY (class_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: class_teacher class_teacher_class_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.class_teacher
    ADD CONSTRAINT class_teacher_class_id_foreign FOREIGN KEY (class_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: class_teacher class_teacher_teacher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.class_teacher
    ADD CONSTRAINT class_teacher_teacher_id_foreign FOREIGN KEY (teacher_id) REFERENCES public.teachers(id) ON DELETE CASCADE;


--
-- Name: materi_pdfs materi_pdfs_chapter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.materi_pdfs
    ADD CONSTRAINT materi_pdfs_chapter_id_foreign FOREIGN KEY (chapter_id) REFERENCES public.chapters(id) ON DELETE CASCADE;


--
-- Name: package_classes package_classes_class_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.package_classes
    ADD CONSTRAINT package_classes_class_id_foreign FOREIGN KEY (class_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: package_classes package_classes_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.package_classes
    ADD CONSTRAINT package_classes_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id) ON DELETE CASCADE;


--
-- Name: profile_siswas profile_siswas_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profile_siswas
    ADD CONSTRAINT profile_siswas_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: questions questions_quiz_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT questions_quiz_id_foreign FOREIGN KEY (quiz_id) REFERENCES public.quizzes(id) ON DELETE CASCADE;


--
-- Name: quiz_attempts quiz_attempts_quiz_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quiz_attempts
    ADD CONSTRAINT quiz_attempts_quiz_id_foreign FOREIGN KEY (quiz_id) REFERENCES public.quizzes(id) ON DELETE CASCADE;


--
-- Name: quiz_attempts quiz_attempts_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quiz_attempts
    ADD CONSTRAINT quiz_attempts_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: quiz_results quiz_results_quiz_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quiz_results
    ADD CONSTRAINT quiz_results_quiz_id_foreign FOREIGN KEY (quiz_id) REFERENCES public.quizzes(id) ON DELETE CASCADE;


--
-- Name: quiz_results quiz_results_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quiz_results
    ADD CONSTRAINT quiz_results_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: quizzes quizzes_class_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.quizzes
    ADD CONSTRAINT quizzes_class_id_foreign FOREIGN KEY (class_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: soal_attempts soal_attempts_soal_set_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_attempts
    ADD CONSTRAINT soal_attempts_soal_set_id_foreign FOREIGN KEY (soal_set_id) REFERENCES public.soal_sets(id) ON DELETE CASCADE;


--
-- Name: soal_attempts soal_attempts_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_attempts
    ADD CONSTRAINT soal_attempts_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: soal_options soal_options_soal_question_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_options
    ADD CONSTRAINT soal_options_soal_question_id_foreign FOREIGN KEY (soal_question_id) REFERENCES public.soal_questions(id) ON DELETE CASCADE;


--
-- Name: soal_questions soal_questions_soal_set_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_questions
    ADD CONSTRAINT soal_questions_soal_set_id_foreign FOREIGN KEY (soal_set_id) REFERENCES public.soal_sets(id) ON DELETE CASCADE;


--
-- Name: soal_sections soal_sections_class_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_sections
    ADD CONSTRAINT soal_sections_class_id_foreign FOREIGN KEY (class_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: soal_sets soal_sets_soal_section_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.soal_sets
    ADD CONSTRAINT soal_sets_soal_section_id_foreign FOREIGN KEY (soal_section_id) REFERENCES public.soal_sections(id) ON DELETE CASCADE;


--
-- Name: students students_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: teachers teachers_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: transactions transactions_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id) ON DELETE SET NULL;


--
-- Name: transactions transactions_payment_method_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_payment_method_id_foreign FOREIGN KEY (payment_method_id) REFERENCES public.payment_methods(id) ON DELETE SET NULL;


--
-- Name: transactions transactions_reviewed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_reviewed_by_foreign FOREIGN KEY (reviewed_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: transactions transactions_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: user_learning_progress user_learning_progress_chapter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_learning_progress
    ADD CONSTRAINT user_learning_progress_chapter_id_foreign FOREIGN KEY (chapter_id) REFERENCES public.chapters(id) ON DELETE CASCADE;


--
-- Name: user_learning_progress user_learning_progress_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_learning_progress
    ADD CONSTRAINT user_learning_progress_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: user_packages user_packages_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_packages
    ADD CONSTRAINT user_packages_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id) ON DELETE CASCADE;


--
-- Name: user_packages user_packages_transaction_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_packages
    ADD CONSTRAINT user_packages_transaction_id_foreign FOREIGN KEY (transaction_id) REFERENCES public.transactions(id) ON DELETE SET NULL;


--
-- Name: user_packages user_packages_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_packages
    ADD CONSTRAINT user_packages_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: videos videos_chapter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.videos
    ADD CONSTRAINT videos_chapter_id_foreign FOREIGN KEY (chapter_id) REFERENCES public.chapters(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict QeCFD1lOz9O65CNshIFaWnoKMbmQqheVB1SD6YSGQWZeyCbWx7OIsSoIanqpU0x


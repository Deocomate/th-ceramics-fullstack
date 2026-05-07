create table bo_noc_chu_van_ct (
    bo_noc_chu_van_ct_id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    images longtext collate utf8mb4_bin not null check (json_valid(`images`)),
    des longtext collate utf8mb4_bin null check (json_valid(`des`)),
    size varchar(255) null,
    size_image varchar(255) null,
    size_des longtext collate utf8mb4_bin null check (json_valid(`size_des`)),
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table cache (
    `key` varchar(255) not null primary key,
    value mediumtext not null,
    expiration int not null
) collate = utf8mb4_unicode_ci;
create index cache_expiration_index on cache (expiration);
create table cache_locks (
    `key` varchar(255) not null primary key,
    owner varchar(255) not null,
    expiration int not null
) collate = utf8mb4_unicode_ci;
create index cache_locks_expiration_index on cache_locks (expiration);
create table den_gom_su (
    den_gom_su_id bigint unsigned auto_increment primary key,
    thumbnail_main varchar(255) not null,
    video varchar(255) null,
    image1 varchar(255) not null,
    image2 varchar(255) not null,
    title2 varchar(30) null,
    image3 varchar(255) not null,
    title3 varchar(30) null,
    image4 varchar(255) not null,
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table den_gom_su_anh (
    den_gom_su_anh_id bigint unsigned auto_increment primary key,
    image varchar(255) not null,
    den_gom_su_id bigint unsigned not null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint den_gom_su_anh_den_gom_su_id_foreign foreign key (den_gom_su_id) references den_gom_su (den_gom_su_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table dinh_muc_gach_co_bat_trang (
    dinh_muc_gach_co_bat_trang_id bigint unsigned auto_increment primary key,
    brick_type varchar(255) not null,
    value int null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint dinh_muc_gach_co_bat_trang_brick_type_unique unique (brick_type)
) collate = utf8mb4_unicode_ci;
create table dinh_muc_gach_hoa_thong_gio (
    dinh_muc_gach_hoa_thong_gio_id bigint unsigned auto_increment primary key,
    brick_type varchar(255) not null,
    value int null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint dinh_muc_gach_hoa_thong_gio_brick_type_unique unique (brick_type)
) collate = utf8mb4_unicode_ci;
create table dinh_muc_gach_trang_tri (
    dinh_muc_gach_trang_tri_id bigint unsigned auto_increment primary key,
    brick_type varchar(255) not null,
    value int null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint dinh_muc_gach_trang_tri_brick_type_unique unique (brick_type)
) collate = utf8mb4_unicode_ci;
create table dinh_muc_ngoi_am_duong (
    dinh_muc_ngoi_am_duong_id bigint unsigned auto_increment primary key,
    roof_type varchar(255) not null,
    tile_type varchar(255) not null,
    ngoi_am int not null,
    ngoi_duong int not null,
    diem int not null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint dinh_muc_ngoi_am_duong_roof_type_tile_type_unique unique (roof_type, tile_type)
) collate = utf8mb4_unicode_ci;
create table dinh_muc_ngoi_hai_co (
    dinh_muc_ngoi_hai_co_id bigint unsigned auto_increment primary key,
    roof_type varchar(255) not null,
    ngoi_tren_mai_go int not null,
    ngoi_tren_mai_be_tong int not null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint dinh_muc_ngoi_hai_co_roof_type_unique unique (roof_type)
) collate = utf8mb4_unicode_ci;
create table dinh_muc_ngoi_hai_van_mieu (
    dinh_muc_ngoi_hai_van_mieu_id bigint unsigned auto_increment primary key,
    roof_type varchar(255) not null,
    ngoi_tren_mai_go int not null,
    ngoi_tren_mai_be_tong int not null,
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table failed_jobs (
    id bigint unsigned auto_increment primary key,
    uuid varchar(255) not null,
    connection text not null,
    queue text not null,
    payload longtext not null,
    exception longtext not null,
    failed_at timestamp default current_timestamp() not null,
    constraint failed_jobs_uuid_unique unique (uuid)
) collate = utf8mb4_unicode_ci;
create table gach_co_bat_trang (
    gach_co_bat_trang_id bigint unsigned auto_increment primary key,
    thumbnail_main varchar(255) not null,
    video varchar(255) null,
    images longtext collate utf8mb4_bin null check (json_valid(`images`)),
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table gach_co_bat_trang_anh (
    gach_co_bat_trang_anh_id bigint unsigned auto_increment primary key,
    image varchar(255) not null,
    gach_co_bat_trang_id bigint unsigned not null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint gach_co_bat_trang_anh_gach_co_bat_trang_id_foreign foreign key (gach_co_bat_trang_id) references gach_co_bat_trang (gach_co_bat_trang_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table gach_co_bat_trang_ct (
    gach_co_bat_trang_ct_id bigint unsigned auto_increment primary key,
    code varchar(50) not null,
    name varchar(255) not null,
    images longtext collate utf8mb4_bin not null check (json_valid(`images`)),
    price int not null,
    des longtext collate utf8mb4_bin null check (json_valid(`des`)),
    size varchar(255) null,
    size_image varchar(255) null,
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null,
    constraint gach_co_bat_trang_ct_code_unique unique (code)
) collate = utf8mb4_unicode_ci;
create table gach_hoa_thong_gio (
    gach_hoa_thong_gio_id bigint unsigned auto_increment primary key,
    image varchar(255) not null,
    video varchar(255) null,
    images longtext collate utf8mb4_bin null check (json_valid(`images`)),
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table gach_hoa_thong_gio_anh (
    gach_hoa_thong_gio_anh_id bigint unsigned auto_increment primary key,
    image varchar(255) not null,
    gach_hoa_thong_gio_id bigint unsigned not null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint gach_hoa_thong_gio_anh_gach_hoa_thong_gio_id_foreign foreign key (gach_hoa_thong_gio_id) references gach_hoa_thong_gio (gach_hoa_thong_gio_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table gach_hoa_thong_gio_ct (
    gach_hoa_thong_gio_ct_id bigint unsigned auto_increment primary key,
    code varchar(50) not null,
    name varchar(255) not null,
    images longtext collate utf8mb4_bin not null check (json_valid(`images`)),
    price int not null,
    des longtext collate utf8mb4_bin null check (json_valid(`des`)),
    size varchar(255) null,
    size_image varchar(255) null,
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null,
    constraint gach_hoa_thong_gio_ct_code_unique unique (code)
) collate = utf8mb4_unicode_ci;
create table gach_trang_tri (
    gach_trang_tri_id bigint unsigned auto_increment primary key,
    thumbnail_main varchar(255) not null,
    video varchar(255) null,
    images longtext collate utf8mb4_bin null check (json_valid(`images`)),
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table dau_an_gach_trang_tri (
    dau_an_gach_trang_tri_id bigint unsigned auto_increment primary key,
    background varchar(255) not null,
    title varchar(255) not null,
    location varchar(255) not null,
    description varchar(255) not null,
    gach_trang_tri_id bigint unsigned not null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint dau_an_gach_trang_tri_gach_trang_tri_id_foreign foreign key (gach_trang_tri_id) references gach_trang_tri (gach_trang_tri_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table gach_trang_tri_ct (
    gach_trang_tri_ct_id bigint unsigned auto_increment primary key,
    code varchar(50) not null,
    name varchar(255) not null,
    images longtext collate utf8mb4_bin not null check (json_valid(`images`)),
    price int not null,
    des longtext collate utf8mb4_bin null check (json_valid(`des`)),
    size varchar(255) null,
    size_image varchar(255) null,
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null,
    constraint gach_trang_tri_ct_code_unique unique (code)
) collate = utf8mb4_unicode_ci;
create table gia_tri_gach_hoa_thong_gio (
    gia_tri_gach_hoa_thong_gio_id bigint unsigned auto_increment primary key,
    background varchar(255) not null,
    image varchar(255) not null,
    title varchar(50) not null,
    desscription longtext not null,
    gach_hoa_thong_gio_id bigint unsigned not null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint gia_tri_gach_hoa_thong_gio_gach_hoa_thong_gio_id_foreign foreign key (gach_hoa_thong_gio_id) references gach_hoa_thong_gio (gach_hoa_thong_gio_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table gia_tri_vuot_troi (
    gia_tri_vuot_troi_id bigint unsigned auto_increment primary key,
    title varchar(50) not null,
    desscription longtext not null,
    image varchar(255) not null,
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table job_batches (
    id varchar(255) not null primary key,
    name varchar(255) not null,
    total_jobs int not null,
    pending_jobs int not null,
    failed_jobs int not null,
    failed_job_ids longtext not null,
    options mediumtext null,
    cancelled_at int null,
    created_at int not null,
    finished_at int null
) collate = utf8mb4_unicode_ci;
create table jobs (
    id bigint unsigned auto_increment primary key,
    queue varchar(255) not null,
    payload longtext not null,
    attempts tinyint unsigned not null,
    reserved_at int unsigned null,
    available_at int unsigned not null,
    created_at int unsigned not null
) collate = utf8mb4_unicode_ci;
create index jobs_queue_index on jobs (queue);
create table lan_can_gom_xu (
    lan_can_gom_xu_id bigint unsigned auto_increment primary key,
    thumbnail_main varchar(255) not null,
    video varchar(255) null,
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table linh_vat_phong_thuy (
    linh_vat_phong_thuy_id bigint unsigned auto_increment primary key,
    thumbnail_main varchar(255) not null,
    video varchar(255) null,
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table linh_vat (
    linh_vat_id bigint unsigned auto_increment primary key,
    image varchar(255) not null,
    title varchar(50) not null,
    description text not null,
    linh_vat_phong_thuy_id bigint unsigned not null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint linh_vat_linh_vat_phong_thuy_id_foreign foreign key (linh_vat_phong_thuy_id) references linh_vat_phong_thuy (linh_vat_phong_thuy_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table linh_vat_phong_thuy_anh (
    linh_vat_phong_thuy_anh_id bigint unsigned auto_increment primary key,
    image varchar(255) not null,
    linh_vat_phong_thuy_id bigint unsigned not null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint linh_vat_phong_thuy_anh_linh_vat_phong_thuy_id_foreign foreign key (linh_vat_phong_thuy_id) references linh_vat_phong_thuy (linh_vat_phong_thuy_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table linh_vat_phong_thuy_ct (
    linh_vat_phong_thuy_ct_id bigint unsigned auto_increment primary key,
    code varchar(50) not null,
    name varchar(255) not null,
    images longtext collate utf8mb4_bin not null check (json_valid(`images`)),
    price int not null,
    des longtext collate utf8mb4_bin null check (json_valid(`des`)),
    size varchar(255) null,
    size_image varchar(255) null,
    size_des longtext collate utf8mb4_bin null check (json_valid(`size_des`)),
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null,
    constraint linh_vat_phong_thuy_ct_code_unique unique (code)
) collate = utf8mb4_unicode_ci;
create table mau_sac_ngoi_am_duong_ct (
    mau_sac_ngoi_am_duong_ct_id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    image varchar(255) not null,
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table migrations (
    id int unsigned auto_increment primary key,
    migration varchar(255) not null,
    batch int not null
) collate = utf8mb4_unicode_ci;
create table ngoi_am_duong (
    ngoi_am_duong_id bigint unsigned auto_increment primary key,
    thumbnail_main varchar(255) not null,
    thumbnail1 varchar(255) not null,
    thumbnail2 varchar(255) not null,
    video varchar(255) null,
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table ngoi_am_duong_ct (
    ngoi_am_duong_ct_id bigint unsigned auto_increment primary key,
    code varchar(50) not null,
    name varchar(255) not null,
    images longtext collate utf8mb4_bin not null check (json_valid(`images`)),
    price int not null,
    des longtext collate utf8mb4_bin null check (json_valid(`des`)),
    size varchar(255) null,
    size_image varchar(255) null,
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null,
    constraint ngoi_am_duong_ct_code_unique unique (code)
) collate = utf8mb4_unicode_ci;
create table ngoi_bo_noc_ct (
    ngoi_bo_noc_ct_id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    images longtext collate utf8mb4_bin not null check (json_valid(`images`)),
    des longtext collate utf8mb4_bin null check (json_valid(`des`)),
    size varchar(255) null,
    size_image varchar(255) null,
    size_des longtext collate utf8mb4_bin null check (json_valid(`size_des`)),
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table ngoi_hai_co_ct (
    ngoi_hai_co_ct_id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    images longtext collate utf8mb4_bin not null check (json_valid(`images`)),
    des longtext collate utf8mb4_bin null check (json_valid(`des`)),
    size varchar(255) null,
    size_image varchar(255) null,
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table mau_sac_ngoi_hai_co_ct (
    mau_sac_ngoi_hai_co_ct_id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    image varchar(255) not null,
    code varchar(50) not null,
    price int not null,
    ngoi_hai_co_ct_id bigint unsigned not null,
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null,
    constraint mau_sac_ngoi_hai_co_ct_code_unique unique (code),
    constraint mau_sac_ngoi_hai_co_ct_ngoi_hai_co_ct_id_foreign foreign key (ngoi_hai_co_ct_id) references ngoi_hai_co_ct (ngoi_hai_co_ct_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table ngoi_hai_van_mieu (
    ngoi_hai_van_mieu_id bigint unsigned auto_increment primary key,
    thumbnail_main varchar(255) not null,
    title1 varchar(50) not null,
    thumbnail1 varchar(255) not null,
    title2 varchar(50) not null,
    thumbnail2 varchar(255) not null,
    title3 varchar(50) not null,
    thumbnail3 varchar(255) not null,
    video varchar(255) null,
    images longtext collate utf8mb4_bin null check (json_valid(`images`)),
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table ngoi_hai_van_mieu_ct (
    ngoi_hai_van_mieu_ct_id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    images longtext collate utf8mb4_bin not null check (json_valid(`images`)),
    price int not null,
    des longtext collate utf8mb4_bin null check (json_valid(`des`)),
    mau_sac_id bigint unsigned not null,
    size varchar(255) null,
    size_image varchar(255) null,
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table mau_sac_ngoi_hai_van_mieu_ct (
    mau_sac_ngoi_hai_van_mieu_ct_id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    image varchar(255) not null,
    code varchar(50) not null,
    price int not null,
    ngoi_hai_van_mieu_ct_id bigint unsigned not null,
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null,
    constraint mau_sac_ngoi_hai_van_mieu_ct_code_unique unique (code),
    constraint fk_mau_sac_van_mieu foreign key (ngoi_hai_van_mieu_ct_id) references ngoi_hai_van_mieu_ct (ngoi_hai_van_mieu_ct_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table password_reset_tokens (
    email varchar(255) not null primary key,
    token varchar(255) not null,
    created_at timestamp null
) collate = utf8mb4_unicode_ci;
create table phan_loai_bo_noc_chu_van_ct (
    phan_loai_bo_noc_chu_van_ct_id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    code varchar(50) not null,
    price int not null,
    bo_noc_chu_van_ct_id bigint unsigned not null,
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null,
    constraint phan_loai_bo_noc_chu_van_ct_code_unique unique (code),
    constraint phan_loai_bo_noc_chu_van_ct_name_unique unique (name),
    constraint fk_phan_loai_chu_van foreign key (bo_noc_chu_van_ct_id) references bo_noc_chu_van_ct (bo_noc_chu_van_ct_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table phan_loai_ngoi_bo_noc_ct (
    phan_loai_ngoi_bo_noc_ct_id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    code varchar(50) not null,
    price int not null,
    ngoi_bo_noc_ct_id bigint unsigned not null,
    is_delete tinyint(1) default 0 not null comment '0: Active, 1: Deleted',
    created_at timestamp null,
    updated_at timestamp null,
    constraint phan_loai_ngoi_bo_noc_ct_code_unique unique (code),
    constraint phan_loai_ngoi_bo_noc_ct_name_unique unique (name),
    constraint fk_phan_loai_bo_noc foreign key (ngoi_bo_noc_ct_id) references ngoi_bo_noc_ct (ngoi_bo_noc_ct_id) on delete cascade
) collate = utf8mb4_unicode_ci;
create table phu_kien_ngoi (
    phu_kien_ngoi_id bigint unsigned auto_increment primary key,
    thumbnail_main varchar(255) not null,
    images longtext collate utf8mb4_bin null check (json_valid(`images`)),
    created_at timestamp null,
    updated_at timestamp null
) collate = utf8mb4_unicode_ci;
create table sessions (
    id varchar(255) not null primary key,
    user_id bigint unsigned null,
    ip_address varchar(45) null,
    user_agent text null,
    payload longtext not null,
    last_activity int not null
) collate = utf8mb4_unicode_ci;
create index sessions_last_activity_index on sessions (last_activity);
create index sessions_user_id_index on sessions (user_id);
create table users (
    id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    role enum ('superadmin', 'admin', 'customer') not null,
    email varchar(255) not null,
    email_verified_at timestamp null,
    password varchar(255) not null,
    remember_token varchar(100) null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint users_email_unique unique (email)
) collate = utf8mb4_unicode_ci;
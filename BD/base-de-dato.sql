CREATE TABLE usuarios (
  id_usuarios INTEGER(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(150) NOT NULL DEFAULT "",
  rol VARCHAR(12) NOT NULL DEFAULT usuario,
  contrasena VARCHAR(150) NOT NULL,
  PRIMARY KEY(id_usuarios)
);

CREATE TABLE tipo_dispositivo (
  id_tipo_dispositivo INTEGER(2) UNSIGNED NOT NULL AUTO_INCREMENT,
  ip2 INTEGER(3) UNSIGNED NOT NULL,
  equipo VARCHAR(30) NOT NULL DEFAULT "",
  PRIMARY KEY(id_tipo_dispositivo, ip2)
);

CREATE TABLE locales (
  id_locales INTEGER(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  ip3 INTEGER(3) UNSIGNED NOT NULL,
  denominacion VARCHAR(100) NOT NULL,
  direccion VARCHAR(150) NOT NULL,
  ciudad VARCHAR(50) NOT NULL,
  PRIMARY KEY(id_locales, ip3)
);

CREATE TABLE usuario_local (
  usuarios_id_usuarios INTEGER(5) UNSIGNED NOT NULL,
  locales_ip3 INTEGER(3) UNSIGNED NOT NULL,
  locales_id_locales INTEGER(4) UNSIGNED NOT NULL,
  PRIMARY KEY(usuarios_id_usuarios, locales_ip3, locales_id_locales),
  INDEX usuario_local_FKIndex2(usuarios_id_usuarios),
  INDEX usuario_local_FKIndex2(locales_id_locales, locales_ip3),
  FOREIGN KEY(usuarios_id_usuarios)
    REFERENCES usuarios(id_usuarios)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(locales_id_locales, locales_ip3)
    REFERENCES locales(id_locales, ip3)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE dispositivos (
  id_dispositivos INTEGER(7) UNSIGNED NOT NULL AUTO_INCREMENT,
  tipo_dispositivo_ip2 INTEGER(3) UNSIGNED NOT NULL,
  locales_ip3 INTEGER(3) UNSIGNED NOT NULL,
  locales_id_locales INTEGER(4) UNSIGNED NOT NULL,
  tipo_dispositivo_id_tipo_dispositivo INTEGER(2) UNSIGNED NOT NULL,
  ip1 INTEGER(3) UNSIGNED NOT NULL,
  ip4 INTEGER(3) UNSIGNED NOT NULL,
  nombre_equipo VARCHAR(50) NOT NULL DEFAULT "",
  PRIMARY KEY(id_dispositivos),
  INDEX dispositivos_FKIndex1(tipo_dispositivo_id_tipo_dispositivo, tipo_dispositivo_ip2),
  INDEX dispositivos_FKIndex2(locales_id_locales, locales_ip3),
  FOREIGN KEY(tipo_dispositivo_id_tipo_dispositivo, tipo_dispositivo_ip2)
    REFERENCES tipo_dispositivo(id_tipo_dispositivo, ip2)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(locales_id_locales, locales_ip3)
    REFERENCES locales(id_locales, ip3)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE reportes (
  id_reportes INTEGER(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  usuarios_id_usuarios INTEGER(5) UNSIGNED NOT NULL,
  locales_id_locales INTEGER(4) UNSIGNED NOT NULL,
  locales_ip3 INTEGER(3) UNSIGNED NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  dispositivos_conectados INTEGER(4) UNSIGNED NOT NULL,
  PRIMARY KEY(id_reportes, usuarios_id_usuarios, locales_id_locales, locales_ip3),
  INDEX reportes_FKIndex1(usuarios_id_usuarios),
  INDEX reportes_FKIndex2(locales_id_locales, locales_ip3),
  FOREIGN KEY(usuarios_id_usuarios)
    REFERENCES usuarios(id_usuarios)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(locales_id_locales, locales_ip3)
    REFERENCES locales(id_locales, ip3)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

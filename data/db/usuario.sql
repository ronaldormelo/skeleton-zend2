SELECT * FROM controller WHERE nm_controller = 'usuario';

INSERT INTO controller (nm_controller) VALUES ('usuario');

INSERT INTO perfil_controller_action (
  id_controller,
  id_action,
  id_perfil
) 
VALUES
  (15, 1, 1),
  (15, 2, 1),
  (15, 3, 1),
  (15, 4, 1),
  (15, 5, 1),  
  (15, 6, 1);
  


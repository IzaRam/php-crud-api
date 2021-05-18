select receita.id, receita.nome
from receita inner join receita_ingrediente
on receita_ingrediente.receita_id=receita.id
where receita_ingrediente.ingrediente_id=$ing_id;

select ingrediente.id, ingrediente.nome
from ingrediente inner join receita_ingrediente
on receita_ingrediente.ingrediente_id=ingrediente.id
where receita_ingrediente.receita_id=$rec_id;

select r.*, i.* from receita r
inner join receita_ingrediente rm on rm.receita_id  = r.id
inner join ingrediente i on i.id = rm.ingrediente_id
where i.id = 3;

select r.*, i.* from receita r
inner join receita_ingrediente rm on rm.receita_id  = r.id
inner join ingrediente i on i.id = rm.ingrediente_id;

<?php 
function URLSeo($input)
{
        $valor = ereg_replace(
                '[^a-z0-9-]',
                '', 
                ereg_replace(
                        ' +',
                        '-',
                        strtr(
                                strtolower($input), 
                                'ÀÁÃÂÉÊÍÓÕÔÚÜÇàáãâéêíóõôúüç', 
                                'AAAAEEIOOOUUCaaaaeeiooouuc'
                        )
                )
        );
        
        return $valor;
}
?>
<?php 
function URLWords($input)
{
        return $var = ereg_replace(" ",", ",$input);
}
?>
<?php 
function gerar_link_seo($input,$substitui = '-',$remover_palavras = true,$array_palavras = array())
{
    $resultado = trim(ereg_replace(' +',' ',preg_replace('/[^a-zA-Z0-9\s]/','',strtolower($input))));
 
    if($remover_palavras) { $resultado = remover_palavras($resultado,$substitui,$array_palavras); }
 
    return str_replace(' ',$substitui,$resultado);
}
function remover_palavras($input,$substitui,$array_palavras = array(),$palavras_unicas = true)
{
    $array_entrada = explode(' ',$input);
 
    $resultado = array();
 
    foreach($array_entrada as $palavra)
    {
        if(!in_array($palavra,$array_palavras) && ($palavras_unica ? !in_array($palavra,$resultado) : true))
        {
            $resultado[] = $palavra;
        }
    }
 
    return implode($substitui,$resultado);
}
$palavras_indesejadas = array('a','um','de','o','é','à','com','pode','da','porque','não');
?>
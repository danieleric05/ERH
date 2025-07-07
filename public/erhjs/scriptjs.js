/**
 * Created by oklastar277 on 03/04/2019.
 */
jQuery(document).ready(function(){

    //remplissage des unite
    jQuery.getJSON('erhjs/douane.php?Idunite=',function(data){

        jQuery.each(data, function(i, resultat) {

            jQuery('#idunite').append("<option value="+resultat.id+">" +resultat.name+ "</option>");

        });

    });

    //remplissage des unite
    jQuery.getJSON('../erhjs/douane.php?IdEquipe=',function(data){

        jQuery.each(data, function(i, resultat) {

            jQuery('#equipeid').append("<option value="+resultat.id+">" +resultat.name+ "</option>");

        });

    });

    //remplissage des departement
    jQuery.getJSON('../erhjs/douane.php?IdDepartement=',function(data){

        jQuery.each(data, function(i, resultat) {

            jQuery('#departementid').append("<option value="+resultat.id+">" +resultat.name+ "</option>");

        });

    });


    //remplissage des pays
    jQuery.getJSON('erhjs/douane.php?Idpays=',function(data){

        jQuery.each(data, function(i, resultat) {

            jQuery('#paysid').append("<option value="+resultat.id+">" +resultat.name+ "</option>");

        });

    });

    //remplissage des niveau etude
    jQuery.getJSON('../erhjs/douane.php?NiveauEtude=',function(data){

        jQuery.each(data, function(i, resultat) {

            jQuery('#niveau_etudeid').append("<option value="+resultat.id+">" +resultat.name+ "</option>");

        });

    });

    //remplissage des niveau etude
    jQuery.getJSON('../erhjs/douane.php?fonctionId=',function(data){

        jQuery.each(data, function(i, resultat) {

            jQuery('#fonction_entrepriseid').append("<option value="+resultat.id+">" +resultat.name+ "</option>");

        });

    });

    //remplissage des categories
    jQuery.getJSON('../erhjs/douane.php?categoriesId=',function(data){

        jQuery.each(data, function(i, resultat) {

            jQuery('#categorieid').append("<option value="+resultat.id+">" +resultat.name+ "</option>");

        });

    });

});

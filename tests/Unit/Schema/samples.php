<?php

const THREE_MODELS_WITH_ATTRIBUTES = <<< 'CODE'
Model1
attribute1
attribute2

Model2
attribute1
attribute2

Model3
attribute1
attribute2
CODE;

const THREE_MODELS_WITH_ATTRIBUTES_DIRTY = <<< 'CODE'


Model1
attribute1
attribute2
  
Model2
attribute1
attribute2
  
  
  
Model3   
attribute1  
attribute2

CODE;

const THREE_ENTITIES_WITH_ATTRIBUTES_AND_DIRECTIVES = <<< 'CODE'
Entity1 d1 d2
a1 d1 d2
a2 d1 d2

Entity2 d1 d2
a1 d1 d2
a2 d1 d2

Entity3 d1 d2
a1 d1 d2
a2 d1 d2
CODE;

const ENTITY_WITH_ATTRIBUTE_DIRECTIVES = <<< 'CODE'
Shop 
score float:8,2 fillable
CODE;

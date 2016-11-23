from __future__ import unicode_literals

from django.db import models

# Create your models here.
class Word(models.Model):
	word = models.CharField(max_length = 50)
	class Meta:
		db_table = 'words'

class Package(models.Model):
	name = models.CharField(max_length = 100)
	description = models.TextField()
	price = models.IntegerField()
	class Meta:
		db_table = 'packages'

class DocumentWord(models.Model):
	doc_id = models.IntegerField(primary_key = True)
	word = models.ForeignKey(Word, on_delete = models.CASCADE)
	freq = models.IntegerField()
	weight = models.DecimalField(max_digits = 3, decimal_places = 3)
	class Meta:
		db_table = 'document_words'

class TfTable(models.Model):
	word = models.ForeignKey(Package, on_delete = models.CASCADE)
	tf = models.IntegerField()
	class Meta:
		db_table = 'tf_tables'

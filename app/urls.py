from django.conf.urls import url

from . import views

urlpatterns = [
    url(r'^$', views.index, name='index'),
    #url(r'^search/(?P<query>\w+)/$', views.search, name='search'),
    #^^ coba buat dapetin query
]
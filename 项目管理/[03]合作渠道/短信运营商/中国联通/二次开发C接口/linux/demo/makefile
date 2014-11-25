# Make sgipapi system version 1.0 programs.
# 
# makefile 1.3 6/3/94
#
# Change the following definitions as appropriate for your site:

HOME = ..
CC        = cc
INCLUDE   = -I. 
LIBDIR    = $(HOME)/lib
CLDDIR    = $(HOME)/include
HEADERS	  = $(CLDDIR)/tssx_sgip.h
LIBERY    = $(LIBDIR)/sgip_api.a  
CFLAGS	  = -g -O -Wall

#change next options for applicationes of yourself
MO	= test_mo.o 
MT	= test_mt.o 
OBJ	= $(MO) $(MT)

all: test_mo test_mt
 
test_mo:$(MO)
	cc -o test_mo $(MO) $(LIBERY) -L -lcurses -lpthread -lm -lc -o $@ 

	@echo " "
	@echo "         	=== Make $@ Success! ==="
	@echo " "

test_mt:$(MT)
	cc -o test_mt $(MT) $(LIBERY) -L -lcurses -lpthread -lm -lc -o $@ 
	@echo " "
	@echo "			=== Make $@ Success! ==="
	@echo " "

clean:
	@rm *.o test_mt test_mo 

$(OBJ): $(HEADERS)
.c.o:
	cc -c -g   $(INCLUDE) $(CFLAGS) $*.c



package com.floms.openbarcode;

/**
 * Created with IntelliJ IDEA.
 * User: Yoel
 * Date: 11/6/13
 * Time: 9:30 PM
 * To change this template use File | Settings | File Templates.
 */
public interface LinearBarcode {
    public void buildSequence();
    public int[] mapSequence(char c, int pos);
    public int[] barcode();
    public String code();
    public int calculateCheckDigit();
}
